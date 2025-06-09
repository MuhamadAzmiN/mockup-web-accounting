<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\AuthorizationChecker;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class OperationalController extends Controller
{
    use AuthorizationChecker;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->checkAuthorization(Auth::user(), ['manage_operational']);
            return $next($request);
        });
    }
   public function index()
    {
      
        $products = collect();
        if (Auth::user()->company){
            $products = Product::where('company_id', Auth::user()->company_id)
            ->where('type', 'operational')
            ->orderBy('created_at', 'desc');
        }else if(Auth::user()->name == "Super Admin"){
            $products = Product::where('type', 'operational');
        }

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $products = $products->where(function ($query) use ($search) {
                $query->where('product_code', 'like', '%' . $search . '%')
                    ->orWhere('external_product_code', 'like', '%' . $search . '%')
                    ->orWhere('product_name', 'like', '%' . $search . '%');
            });
        }


        $products = $products->paginate(10);

        return view('pages.operational.index', compact('products'));
    }


    public function create()
    {

        $branches = collect();
        $user = Auth::user();
        if ($user && $user->company){
            $branches = $user->company->branches()->select('id', 'name')->get();
        }else if($user->name == "Super Admin"){
            $branches = DB::table('branches')->select('id', 'name')->get();
        }
        return view('pages.operational.create', compact('branches'));
    }


   public function store(Request $request)
{
    $user = Auth::user();
    dd($user->getRoleNames());

    $request->validate([
    'product_code' => [
        'required',
        Rule::unique('products')->where(function ($query) use ($request) {
            return $query->where('type', $request->type);
        })
    ],
    'external_product_code' => [
        'nullable',
        Rule::unique('products')->where(function ($query) use ($request) {
            return $query->where('type', $request->type);
        })
    ],
    'product_name' => 'required|max:255',
    'stock' => 'required|integer|min:0',
    'selling_price' => 'required|numeric|min:0',
    'information' => 'nullable|string',
    'company_id' => (Auth::user()->name == 'Super Admin') ? 'nullable' : 'required',
]);




    $data = $request->all();

    if (!Str::startsWith($data['product_code'], 'PRODOPRI')) {
        $data['product_code'] = 'PRODOPRI' . $data['product_code'];
    }

    if (!empty($data['external_product_code']) && !Str::startsWith($data['external_product_code'], 'EXTPRODOPRI')) {
        $data['external_product_code'] = 'EXTPRODOPRI' . $data['external_product_code'];
    }

    DB::beginTransaction();

    try {
        // Pindahkan Product::create() ke dalam transaksi
        $product = Product::create($data);

        $po = \App\Models\PurchaseOrder::create([
            'po_number'    => 'PO-' . strtoupper(uniqid()),
            'order_date'   => now(),
            'status'       => 'completed',
            'company_id'   => Auth::user()->company_id,
            'branch_id'    => $request->branch_id,
            'total_amount' => $request->selling_price * $request->stock,
            'product_id'   => $product->id,
        ]);

        $journal = \App\Models\Journal::create([
            'journal_date'   => now(),
            'description'    => 'Pembelian Tunai ' . number_format($po->total_amount, 0, ',', '.'),
            'company_id'     =>  Auth::user()->company_id,
            'branch_id'      => $po->branch_id,
            'created_by'     => Auth::user()->name ?? 'System',
        ]);

        $inventoryAccount = \App\Models\Account::where('code', '5-501')->first(); // Beban ATK
        $payableAccount   = \App\Models\Account::where('code', '1-101')->first(); // Kas (karena pembelian tunai)

        if (!$inventoryAccount || !$payableAccount) {
            throw new \Exception("Account Persediaan atau Kas tidak ditemukan.");
        }

        \App\Models\JournalEntries::create([
            'journal_id'     => $journal->id,
            'account_id'     => $inventoryAccount->id,
            'debit'          => $po->total_amount,
            'credit'         => 0,
            'source_event'   => 'PEMBELIAN TUNAI',
            'source_ref_id'  => $po->po_number,
        ]);

        \App\Models\JournalEntries::create([
            'journal_id'     => $journal->id,
            'account_id'     => $payableAccount->id,
            'debit'          => 0,
            'credit'         => $po->total_amount,
            'source_event'   => 'PEMBELIAN TUNAI',
            'source_ref_id'  => $po->po_number,
        ]);

        DB::commit();

        return redirect()->route('operational')->with('success', 'Purchase Order, Jurnal, dan Produk berhasil diproses.');
    } catch (\Exception $e) {
        DB::rollBack(); 
        dd($e->getMessage());
        return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}


    public function show($id)
    {
        $product = Product::findOrFail($id)->where('company_id', Auth::user()->company_id)
            ->where('type', 'operational') // filter dulu hanya operational
            ->first();
    

        return view('pages.operational.detail', compact('product'));
    }



    public function destroy($id)
{
    DB::beginTransaction();

    try {
        logger("Menghapus produk: " . $id);

        // Ambil produk dulu
        $product = \App\Models\Product::findOrFail($id);

        // Cari purchase order terkait
        $purchaseOrder = \App\Models\PurchaseOrder::where('product_id', $id)->first();

        if ($purchaseOrder) {
            logger("PO ditemukan: " . $purchaseOrder->po_number);

            // Hapus journal entries
            \App\Models\JournalEntries::where('source_ref_id', $purchaseOrder->po_number)->delete();
            // Hapus jurnal
            \App\Models\Journal::where('description', 'like', '%'.$purchaseOrder->po_number.'%')->delete();

            // Hapus purchase order
            $purchaseOrder->delete();
        } else {
            logger("PO tidak ditemukan untuk product_id " . $id);
        }

        // Hapus produk
        $product->delete();

        DB::commit();

        return redirect()->route('operational')->with('success', 'Produk dan semua data terkait berhasil dihapus.');
    } catch (\Exception $e) {
        DB::rollBack();
        logger('Gagal hapus: ' . $e->getMessage());
        return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
    }
}




}
