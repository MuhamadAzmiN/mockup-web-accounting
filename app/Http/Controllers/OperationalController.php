<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Journal;
use App\Models\JournalEntries;
use App\Models\Product;
use App\Models\PurchaseOrder;
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
        }else if(Auth::user()->hasRole('super-admin')){
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
        }else if($user->hasRole('super-admin')){
            $branches = DB::table('branches')->select('id', 'name')->get();
        }
        return view('pages.operational.create', compact('branches'));
    }


   public function store(Request $request)
{

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
    'company_id' => (Auth::user()->hasRole('super-admin')) ? 'nullable' : 'required',
]);



    if (Auth::user()->hasRole('super-admin')) {
    // Ambil company_id dari branch yang dipilih
    $branch = \App\Models\Branch::find($request->branch_id);
    if (!$branch) {
        return back()->with('error', 'Branch tidak ditemukan.');
    }

    // Set company_id ke dalam data request
    $request->merge([
        'company_id' => $branch->company_id,
    ]);
    }




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
        $companyId = Auth::user()->hasRole('super-admin')
        ? \App\Models\Branch::findOrFail($request->branch_id)->company_id
        : Auth::user()->company_id;

        $po = \App\Models\PurchaseOrder::create([
            'po_number'    => 'PO-' . strtoupper(uniqid()),
            'order_date'   => now(),
            'status'       => 'completed',
            'company_id'   => $companyId,
            'branch_id'    => $request->branch_id,
            'total_amount' => $request->selling_price * $request->stock,
            'product_id'   => $product->id,
            
            
        ]);

        $journal = \App\Models\Journal::create([
            'journal_date'   => now(),
            'description'    => 'Pembelian Tunai ' . number_format($po->total_amount, 0, ',', '.'),
            'company_id'     =>  $companyId,
            'branch_id'      => $po->branch_id,
            'created_by'     => Auth::user()->name ?? 'System',
            'purchase_order_id' => $po->id
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
        $product = Product::findOrFail($id);
        
        $purchaseOrder = PurchaseOrder::where('product_id', $id)->first();
        if ($purchaseOrder) {
            logger("PO ditemukan: " . $purchaseOrder->po_number);
            
            $journal = Journal::where('purchase_order_id', $purchaseOrder->id)->first();
            if ($journal) {
                logger("Jurnal ditemukan: " . $journal->description);
                
                // Cek apakah sudah ada jurnal pembalikan untuk penghapusan
                $existingDeletionReversal = JournalEntries::where('journal_id', $journal->id)
                    ->where('source_event', 'PEMBALIKAN - PENGHAPUSAN PRODUK')
                    ->first();
                    
                if ($existingDeletionReversal) {
                    return back()->with('error', 'Jurnal pembalik untuk penghapusan produk ini sudah pernah dibuat.');
                }
                
                // LANGKAH 1: Buat jurnal balik dari entri yang masih aktif (bukan pembalikan)
                $activeEntries = JournalEntries::where('journal_id', $journal->id)
                    ->where('source_event', 'PEMBELIAN TUNAI')
                    ->get();
                
                if ($activeEntries->count() > 0) {
                    foreach ($activeEntries as $entry) {
                        JournalEntries::create([
                            'journal_id' => $journal->id,
                            'account_id' => $entry->account_id,
                            'debit' => $entry->credit ?? 0, // Balik: credit jadi debit
                            'credit' => $entry->debit ?? 0, // Balik: debit jadi credit
                            'source_event' => 'PEMBALIKAN - PENGHAPUSAN PRODUK',
                            'source_ref_id' => $purchaseOrder->po_number,
                        ]);
                    }
                    
                    logger("Jurnal pembalikan dibuat untuk " . $activeEntries->count() . " entri");
                } else {
                    logger("Tidak ada entri aktif yang perlu dibalik");
                }
                
                // Update deskripsi jurnal untuk menandai sebagai reversed
                $journal->update([
                    'description' => $journal->description . ' - REVERSED (PRODUK DIHAPUS)'
                ]);
                
            } else {
                logger("Jurnal tidak ditemukan untuk PO: " . $purchaseOrder->po_number);
            }
            
            // Tandai PO sebagai inactive
            $purchaseOrder->update(['status' => 'inactive']);
            logger("PO " . $purchaseOrder->po_number . " diubah menjadi inactive");
            
        } else {
            logger("PO tidak ditemukan untuk product_id " . $id);
        }
        
        // Hapus produk
        $product->delete();
        logger("Produk berhasil dihapus");
        
        DB::commit();
        
        return redirect()->route('operational')->with('success', 'Produk berhasil dihapus, PO dinonaktifkan, dan jurnal dibalik.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        logger('Gagal hapus produk: ' . $e->getMessage());
        return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
    }
}


public function edit($id){
       $branches = collect();
        $user = Auth::user();
        if ($user && $user->company){
            $branches = $user->company->branches()->select('id', 'name')->get();
        }else if($user->hasRole('super-admin')){
            $branches = DB::table('branches')->select('id', 'name')->get();
        }

        $product = Product::find($id);
        return view('pages.operational.edit', compact('branches', 'product'));

}



public function update(Request $request, $id)
{
    $request->validate([
        'product_code' => [
            'required',
            Rule::unique('products')->ignore($id)->where(function ($query) use ($request) {
                return $query->where('type', $request->type);
            }),
        ],
        'external_product_code' => [
            'nullable',
            Rule::unique('products')->ignore($id)->where(function ($query) use ($request) {
                return $query->where('type', $request->type);
            }),
        ],
        'product_name' => 'required|max:255',
        'stock' => 'required|integer|min:0',
        'selling_price' => 'required|numeric|min:0',
        'information' => 'nullable|string',
        'company_id' => (Auth::user()->hasRole('super-admin')) ? 'nullable' : 'required',
    ]);

    DB::beginTransaction();

    try {
        $product = Product::findOrFail($id);

        // Tambahkan prefix jika tidak ada
        if (!Str::startsWith($request->product_code, 'PRODOPRI')) {
            $request['product_code'] = 'PRODOPRI' . $request->product_code;
        }

        if (!empty($request->external_product_code) && !Str::startsWith($request->external_product_code, 'EXTPRODOPRI')) {
            $request['external_product_code'] = 'EXTPRODOPRI' . $request->external_product_code;
        }

        // Jika super admin, ambil company_id dari branch
        if (Auth::user()->hasRole('super-admin')) {
            $branch = Branch::find($request->branch_id);
            if (!$branch) {
                return back()->with('error', 'Branch tidak ditemukan.');
            }
            $request->merge(['company_id' => $branch->company_id]);
        }

        // Update produk
        $product->update($request->all());

        // Update PO
        $purchaseOrder = PurchaseOrder::where('product_id', $product->id)->first();
        if ($purchaseOrder) {
            // Simpan total amount lama untuk jurnal balik
            $oldTotalAmount = $purchaseOrder->total_amount;
            
            // Update PO dengan nilai baru
            $newTotalAmount = $request->stock * $request->selling_price;
            $purchaseOrder->update([
                'total_amount' => $newTotalAmount,
                'branch_id' => $request->branch_id,
            ]);

            // Update Jurnal
            $journal = Journal::where('purchase_order_id', $purchaseOrder->id)->first();
            if ($journal) {
                $journal->update([
                    'description' => 'Pembelian Tunai ' . number_format($newTotalAmount, 0, ',', '.'),
                    'branch_id' => $request->branch_id,
                ]);

                // LANGKAH 1: Buat jurnal balik dari entri lama
                $oldEntries = JournalEntries::where('journal_id', $journal->id)
                    ->where('source_event', 'PEMBELIAN TUNAI')
                    ->get();
                
                foreach ($oldEntries as $entry) {
                    JournalEntries::create([
                        'journal_id' => $journal->id,
                        'account_id' => $entry->account_id,
                        'debit' => $entry->credit, // Balik: debit jadi credit
                        'credit' => $entry->debit, // Balik: credit jadi debit
                        'source_event' => 'PEMBALIKAN - PEMBELIAN TUNAI',
                        'source_ref_id' => $entry->source_ref_id,
                    ]);
                }

                // LANGKAH 2: Tambahkan entri jurnal baru dengan nilai baru
                $inventoryAccount = Account::where('code', '5-501')->first();
                $payableAccount = Account::where('code', '1-101')->first();

                if (!$inventoryAccount || !$payableAccount) {
                    throw new \Exception("Account Persediaan atau Kas tidak ditemukan.");
                }

                // Jurnal baru untuk persediaan (Debit)
                JournalEntries::create([
                    'journal_id' => $journal->id,
                    'account_id' => $inventoryAccount->id,
                    'debit' => $newTotalAmount,
                    'credit' => 0,
                    'source_event' => 'PEMBELIAN TUNAI',
                    'source_ref_id' => $purchaseOrder->po_number,
                ]);

                // Jurnal baru untuk kas (Credit)
                JournalEntries::create([
                    'journal_id' => $journal->id,
                    'account_id' => $payableAccount->id,
                    'debit' => 0,
                    'credit' => $newTotalAmount,
                    'source_event' => 'PEMBELIAN TUNAI',
                    'source_ref_id' => $purchaseOrder->po_number,
                ]);
            }
        }

        DB::commit();

        return redirect()->route('operational')->with('success', 'Data produk, PO, dan jurnal berhasil diperbarui.');
    } catch (\Exception $e) {
        DB::rollBack();
        logger("Gagal update produk: " . $e->getMessage());
        return back()->with('error', 'Gagal update data: ' . $e->getMessage());
    }
}










}
