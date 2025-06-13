<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalEntries;
use App\Models\Product;
use App\Models\ProductSales;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Traits\AuthorizationChecker;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    use AuthorizationChecker;
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         $this->checkAuthorization(auth()->user(), ['manage_sales']);
    //         return $next($request);
    //     });
    // }

    
    public function index()
{
    $query = Sales::with(['saleItems.product'])->orderBy('created_at', 'desc');

    // Filter berdasarkan company jika bukan super-admin
    if (FacadesAuth::user()->company) {
        $query->where('company_id', FacadesAuth::user()->company_id);
    }

    // Fitur search (case-insensitive)
    if (request()->filled('search')) {
        $search = strtolower(request('search'));

        $query->where(function ($q) use ($search) {
            $q->where(DB::raw('LOWER(invoice_number)'), 'like', '%' . $search . '%')
              ->orWhere(DB::raw('LOWER(customer_name)'), 'like', '%' . $search . '%')
              ->orWhere(DB::raw('LOWER(description)'), 'like', '%' . $search . '%');
        });
    }

    $productSales = $query->paginate(10);
  

    return view('pages.sales.index', compact('productSales'));
}
    public function create()
    {
        $products = Product::select('id', 'product_name', 'product_code', 'stock', 'selling_price',  'company_id', 'branch_id')->where("company_id", FacadesAuth::user()->company_id ?? 1)->where("stock", ">", "0")->where('type', 'inventory')->get();
        return view('pages.sales.create', compact('products'));
    }

   public function store(Request $request)
   {
    try {
        // Validasi input
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:9999',
            'total_price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        $totalPrice = 0;
        $productItems = [];

        foreach ($request->products as $item) {
            $product = \App\Models\Product::findOrFail($item['id']);
            $quantity = (int) $item['quantity'];

            if ($product->stock < $quantity) {
                throw new \Exception("Stok produk {$product->product_name} tidak mencukupi. Stok tersedia: {$product->stock}");
            }

            $subtotal = $quantity * $request->total_price ;
            $totalPrice += $subtotal;

            $productItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'profit' => $quantity * ($product->selling_price - ($product->cost_price ?? 0)),
            ];
        }

        // Simpan ke tabel sales
        $sale = \App\Models\Sales::create([
            'date' => $request->date,
            'total_price' => $totalPrice,
            'notes' => $request->notes,
            'company_id' => FacadesAuth::user()->company_id ?? 1,
        ]);

        // Simpan ke tabel sale_items dan update stock produk
        foreach ($productItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            \App\Models\SalesItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'sell_price' => $product->selling_price,
                'cost_price' => $product->cost_price ?? 0,
                'subtotal' => $item['subtotal'],
                'profit' => $item['profit'],
            ]);

            $product->decrement('stock', $quantity);
        }

        // ===============================
        // Tambahkan Jurnal Transaksi
        // ===============================
        $journal = \App\Models\Journal::create([
            'journal_date' => now(),
            'description' => 'Penjualan tunai ' . number_format($item['subtotal'], 0, ',', '.') . ' untuk Sale #' . $sale->id,
            'company_id' => FacadesAuth::user()->company_id ?? 1,
            'branch_id' => FacadesAuth::user()->branch_id ?? 1,
            'created_by' => FacadesAuth::user()->name ?? 'System',
        ]);

        $salesAccount = \App\Models\Account::where('code', '4-401')->first(); // Penjualan
        $cashAccount  = \App\Models\Account::where('code', '1-101')->first(); // Kas

        if (!$salesAccount || !$cashAccount) {
            throw new \Exception("Akun penjualan atau kas tidak ditemukan.");
        }

        \App\Models\JournalEntries::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'debit' => $sale->total_price,
            'credit' => 0,
            'source_event' => 'PENJUALAN TUNAI',
            'source_ref_id' => $sale->id,
            
        ]);

        \App\Models\JournalEntries::create([
            'journal_id' => $journal->id,
            'account_id' => $salesAccount->id,
            'debit' => 0,
            'credit' => $sale->total_price,
            'source_event' => 'PENJUALAN TUNAI',
            'source_ref_id' => $sale->id,
        ]);

        DB::commit();

        return redirect()->route('sales')->with('success', 'Penjualan & jurnal berhasil ditambahkan!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
    }

public function update(Request $request, $id)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:9999',
        ]);

        DB::beginTransaction();

        $sale = \App\Models\Sales::findOrFail($id);
        $oldTotalPrice = $sale->total_price; // Simpan nilai lama untuk reversal

        // ========== STOCK MANAGEMENT (seperti yang sudah ada) ==========
        // Ambil semua item lama dari penjualan ini
        $oldItems = \App\Models\SalesItem::where('sale_id', $sale->id)->get();

        // Kembalikan stok untuk item lama
        foreach ($oldItems as $oldItem) {
            $product = \App\Models\Product::find($oldItem->product_id);
            if ($product) {
                $product->increment('stock', $oldItem->quantity);
            }
        }

        // Hapus item lama
        \App\Models\SalesItem::where('sale_id', $sale->id)->delete();

        // ========== JOURNAL REVERSAL ==========
        // 1. Buat jurnal pembalik untuk nilai lama
        $reversalJournal = \App\Models\Journal::create([
            'journal_date' => now(),
            'description' => 'Jurnal pembalik untuk update Sale #' . $sale->id . ' (Nilai Lama: Rp' . number_format($oldTotalPrice) . ')',
            'company_id' => FacadesAuth::user()->company_id ?? 1,
            'branch_id' => FacadesAuth::user()->branch_id ?? 1,
            'created_by' => FacadesAuth::user()->name ?? 'System',
        ]);

        $salesAccount = \App\Models\Account::where('code', '1-101')->first(); // Penjualan
        $cashAccount  = \App\Models\Account::where('code', '1-102')->first(); // Kas

        if (!$salesAccount || !$cashAccount) {
            throw new \Exception("Akun penjualan atau kas tidak ditemukan.");
        }

        // Entry jurnal pembalik (kebalikan dari jurnal asli)
        \App\Models\JournalEntries::create([
            'journal_id' => $reversalJournal->id,
            'account_id' => $cashAccount->id,
            'debit' => 0, // Kebalikan: kredit kas (mengurangi kas)
            'credit' => $oldTotalPrice,
            'source_event' => 'SALE_REVERSAL',
            'source_ref_id' => $sale->id,
        ]);

        \App\Models\JournalEntries::create([
            'journal_id' => $reversalJournal->id,
            'account_id' => $salesAccount->id,
            'debit' => $oldTotalPrice, // Kebalikan: debit penjualan (mengurangi pendapatan)
            'credit' => 0,
            'source_event' => 'SALE_REVERSAL',
            'source_ref_id' => $sale->id,
        ]);

        // ========== PROSES DATA BARU ==========
        $totalPrice = 0;
        $productItems = [];

        // Periksa stok untuk item baru dan hitung total
        foreach ($request->products as $item) {
            $product = \App\Models\Product::findOrFail($item['id']);
            $quantity = (int) $item['quantity'];

            if ($product->stock < $quantity) {
                throw new \Exception("Stok produk {$product->product_name} tidak mencukupi. Stok tersedia: {$product->stock}");
            }

            $subtotal = $quantity * $product->selling_price;
            $totalPrice += $subtotal;

            $productItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'profit' => $quantity * ($product->selling_price - ($product->cost_price ?? 0)),
            ];
        }

        // Update data penjualan
        $sale->update([
            'date' => $request->date,
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        // Simpan item baru dan kurangi stok
        foreach ($productItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            \App\Models\SalesItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'sell_price' => $product->selling_price,
                'cost_price' => $product->cost_price ?? 0,
                'subtotal' => $item['subtotal'],
                'profit' => $item['profit'],
            ]);

            $product->decrement('stock', $quantity);
        }

        // ========== JURNAL BARU ==========
        // 2. Buat jurnal baru untuk nilai yang baru
        $newJournal = \App\Models\Journal::create([
            'journal_date' => $request->date, // Gunakan tanggal dari form
            'description' => 'Jurnal penjualan update Sale #' . $sale->id . ' (Nilai Baru: Rp' . number_format($totalPrice) . ')',
            'company_id' => FacadesAuth::user()->company_id ?? 1,
            'branch_id' => FacadesAuth::user()->branch_id ?? 1,
            'created_by' => FacadesAuth::user()->name ?? 'System',
        ]);

        // Entry jurnal baru
        \App\Models\JournalEntries::create([
            'journal_id' => $newJournal->id,
            'account_id' => $cashAccount->id,
            'debit' => $totalPrice, // Debit kas (menambah kas)
            'credit' => 0,
            'source_event' => 'SALE',
            'source_ref_id' => $sale->id,
        ]);

        \App\Models\JournalEntries::create([
            'journal_id' => $newJournal->id,
            'account_id' => $salesAccount->id,
            'debit' => 0,
            'credit' => $totalPrice, // Kredit penjualan (menambah pendapatan)
            'source_event' => 'SALE',
            'source_ref_id' => $sale->id,
        ]);

        // ========== HAPUS/NONAKTIFKAN JURNAL LAMA ==========
        // Opsional: Hapus atau tandai jurnal lama sebagai tidak aktif
        $oldJournal = \App\Models\Journal::where('description', 'like', '%Sale #' . $sale->id . '%')
                                        ->where('id', '!=', $reversalJournal->id)
                                        ->where('id', '!=', $newJournal->id)
                                        ->first();
        
        if ($oldJournal) {
            // Opsi 1: Hapus jurnal lama beserta entries-nya
            \App\Models\JournalEntries::where('journal_id', $oldJournal->id)->delete();
            $oldJournal->delete();
            
            // Opsi 2: Atau tandai sebagai tidak aktif (jika ada field status)
            // $oldJournal->update(['status' => 'inactive', 'notes' => 'Replaced by reversal and new journal']);
        }

        DB::commit();

        return redirect()->route('sales')->with('success', 'Penjualan berhasil diupdate! Jurnal lama telah dibalik dan jurnal baru telah dibuat.');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}


public function edit($id)
{
    
    $sale = Sales::with(['saleItems.product'])->findOrFail($id);
    $products = collect();
    if (FacadesAuth::user()->hasRole('super-admin')) {
        $products = Product::where('type', 'inventory')->get();
    }else if(FacadesAuth::user()->company){
        $products = Product::where('company_id', FacadesAuth::user()->company_id)
            ->where('type', 'inventory') // filter dulu hanya operational
            ->get();
    }
    
    return view('pages.sales.edit', compact('sale', 'products'));
}

public function show($id)
{
        // Ambil data sale dengan relasi saleItems dan product
        $sale = Sales::with(['saleItems.product'])->findOrFail($id);
 
        
        return view('pages.sales.detail', compact('sale'));
}
public function destroy($id)
{
    DB::beginTransaction();
    try {
        logger("Menghapus penjualan: " . $id);
        $sale = Sales::findOrFail($id);

        // Ambil total harga lama sebelum dihapus
        $totalHargaLama = $sale->total_price;

        // ========== RESTORE STOCK ==========
        $saleItems = SalesItem::where('sale_id', $sale->id)->get();
        foreach ($saleItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
                logger("Stok produk {$product->product_name} dikembalikan: +{$item->quantity}");
            }
        }

        // ========== JOURNAL REVERSAL (JURNAL LAMA DIBALIK) ==========
        $journal = Journal::where('description', 'like', '%Sale #' . $sale->id . '%')
                         ->whereNotLike('description', '%REVERSED%')
                         ->first();

        if ($journal) {
            logger("Jurnal ditemukan: " . $journal->description);

            $existingReversal = JournalEntries::where('journal_id', $journal->id)
                ->where('source_event', 'PEMBALIKAN - PENJUALAN LAMA')
                ->first();

            if ($existingReversal) {
                DB::rollBack();
                return back()->with('error', 'Jurnal pembalik untuk penghapusan penjualan ini sudah ada.');
            }

            $activeEntries = JournalEntries::where('journal_id', $journal->id)
                ->where('source_event', 'SALE')
                ->get();

            foreach ($activeEntries as $entry) {
                JournalEntries::create([
                    'journal_id' => $journal->id,
                    'account_id' => $entry->account_id,
                    'debit' => $entry->credit ?? 0,
                    'credit' => $entry->debit ?? 0,
                    'source_event' => 'PEMBALIKAN - PENJUALAN LAMA',
                    'source_ref_id' => $sale->id,
                ]);
            }

            $journal->update([
                'description' => $journal->description . ' - REVERSED'
            ]);
            logger("Jurnal dibalik dan ditandai REVERSED");
        } else {
            logger("Jurnal tidak ditemukan untuk Sale #" . $sale->id);
        }

        // ========== BUAT JURNAL BARU (NILAI BARU) ==========
        // Catatan: Ini asumsinya kamu ingin membuat ulang jurnal baru untuk penjualan yang sudah dikoreksi
        $newJournal = Journal::create([
            'journal_date' => now(),
            'description' => 'Penjualan baru setelah koreksi Sale #' . $sale->id,
            'company_id' => FacadesAuth::user()->company_id ?? 1,
            'branch_id' => FacadesAuth::user()->branch_id ?? 1,
            'created_by' => FacadesAuth::user()->name ?? 'System',
        ]);

        // Misal akun-akun telah ditentukan:
        $cashAccount = Account::where('name', 'Kas')->first(); // atau berdasarkan account_id default
        $salesAccount = Account::where('name', 'Penjualan')->first();

        if ($cashAccount && $salesAccount) {
            JournalEntries::create([
                'journal_id' => $newJournal->id,
                'account_id' => $cashAccount->id,
                'debit' => $totalHargaLama,
                'credit' => 0,
                'source_event' => 'PENJUALAN BARU',
                'source_ref_id' => $sale->id,
            ]);

            JournalEntries::create([
                'journal_id' => $newJournal->id,
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $totalHargaLama,
                'source_event' => 'PENJUALAN BARU',
                'source_ref_id' => $sale->id,
            ]);

            logger("Jurnal baru dibuat dengan nilai: Rp" . number_format($totalHargaLama, 0, ',', '.'));
        } else {
            logger("Akun kas atau penjualan tidak ditemukan!");
        }

        // ========== DELETE SALE ==========
        SalesItem::where('sale_id', $sale->id)->delete();
        $sale->delete();
        logger("Penjualan #{$sale->id} berhasil dihapus");

        DB::commit();
        return redirect()->route('sales')->with('success', 'Penjualan dihapus, stok dikembalikan, jurnal lama dibalik, jurnal baru dibuat.');
    } catch (\Exception $e) {
        DB::rollBack();
        logger('Gagal hapus penjualan: ' . $e->getMessage());
        return back()->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
    }
}






}
