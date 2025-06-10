<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Traits\AuthorizationChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductProcurementController extends Controller
{
    use AuthorizationChecker;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->checkAuthorization(Auth::user(), ['manage_product_procurement']);
            return $next($request);
        });
    }
    
    public function index()
{
     $query = PurchaseOrder::with(['company', 'branch', 'product'])
        ->whereHas('product', function ($q) {
            $q->where('type', 'inventory');
        })
        ->orderBy('created_at', 'desc');

    if (Auth::user()->company) {
        $query->where('company_id', Auth::user()->company_id);
    }

    if (request()->has('search') && request('search') != '') {
        $search = request('search');
        $query->where(function ($q) use ($search) {
            $q->where('po_number', 'like', '%' . $search . '%')
              ->orWhereHas('company', function ($q2) use ($search) {
                  $q2->where('name', 'like', '%' . $search . '%');
              })
              ->orWhereHas('branch', function ($q2) use ($search) {
                  $q2->where('name', 'like', '%' . $search . '%');
              })
              ->orWhereHas('product', function ($q2) use ($search) {
                  $q2->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('product_code', 'like', '%' . $search . '%');
              });
        });
    }

    $purchaseOrders = $query->paginate(10);

    return view('pages.productProcurement.index', compact('purchaseOrders'));
}


    public function create()
{
    $products = collect();
    $branches = collect();

    if (Auth::user()->company) {
        $products = Product::select('id', 'product_name', 'product_code')
            ->where('company_id', Auth::user()->company_id)
            ->where('stock', '>', -1) // Gunakan angka, bukan string
            ->where('type', 'inventory')
            ->get();

        $branches = Auth::user()->company->branches()
            ->select('id', 'name')
            ->get();
    } elseif (Auth::user()->hasRole('super-admin')) {
        $products = Product::select('id', 'product_name', 'product_code')
            ->where('stock', '>', -1)
            ->where('type', 'inventory')
            ->get();

        $branches = DB::table('branches')
            ->select('id', 'name')
            ->get();
    }

    return view('pages.productProcurement.create', compact('products', 'branches'));
}


    public function store(Request $request)
    {
        $request->validate([
            'po_number'    => 'required|unique:purchase_orders',
            'order_date'   => 'required|date',
            // 'status'       => 'required',
            'company_id'   => 'required',
            'branch_id'    => 'required',
            'total_amount' => 'required|numeric',
            'items'        => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create purchase order
            $po = \App\Models\PurchaseOrder::create([
                'po_number'    => $request->po_number,
                'order_date'   => $request->order_date,
                'status'       => $request->status,
                'company_id'   => Auth::user()->company_id,
                'branch_id'    => $request->branch_id,
                'total_amount' => $request->total_amount,
                'product_id'   => $request->items[0]['product_id'],
            ]);

            // 2. Create journal
            $journal = \App\Models\Journal::create([
                'journal_date'   => now(),
                'description'    => 'Pembelian Tunai ' . number_format($po->total_amount, 0, ',', '.'),
                'company_id'     =>  Auth::user()->company_id,
                'branch_id'      => $po->branch_id,
                'created_by'     => Auth::user()->name ?? 'System',
            ]);

            // 3. Ambil account dari tabel accounts
            $inventoryAccount = \App\Models\Account::where('code', '5-501')->first(); // Beban ATK
            $payableAccount = \App\Models\Account::where('code', '1-101')->first(); // Utang Usaha


            if (!$inventoryAccount || !$payableAccount) {
                throw new \Exception("Account Persediaan atau Hutang tidak ditemukan.");
            }

            // 4. Ambil saldo terakhir jika diperlukan (optional, untuk running_balance)
            // ... (boleh kamu tambahkan sesuai kebutuhan)

            // 5. Buat journal entries (debit dan kredit)
            \App\Models\JournalEntries::create([
                'journal_id'     => $journal->id,
                'account_id'     => $inventoryAccount->id,
                'debit'          => $po->total_amount,
                'credit'         => 0,
                'source_event'   => 'PEMBALIAN TUNAI',
                'source_ref_id'  => $po->po_number,
            ]);

            \App\Models\JournalEntries::create([
                'journal_id'     => $journal->id,
                'account_id'     =>     $payableAccount->id,
                'debit'          => 0,
                'credit'         => $po->total_amount,
                'source_event'   => 'PEMBELIAN TUNAI',
                'source_ref_id'  => $po->po_number,
            ]);

            // 6. Update stok produk
            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) {
                    $product->stock += $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return redirect()->route('procurement')->with('success', 'Purchase Order, Jurnal, dan Stok berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        $po->delete();

        return redirect()->route('procurement')->with('success', 'Purchase Order berhasil dihapus.');
    }


    public function getByBranch($branchId)
    {
        $products = Product::where('branch_id', $branchId)->get();

        return response()->json($products);
    }


    
}
