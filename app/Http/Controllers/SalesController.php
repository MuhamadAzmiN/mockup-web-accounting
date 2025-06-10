<?php

namespace App\Http\Controllers;

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
        $productSales = collect();
        
          if(FacadesAuth::user()->company){
            $productSales = Sales::where('company_id', FacadesAuth::user()->company_id)->orderBy('created_at', 'desc')->paginate(10);
        }else if(FacadesAuth::user()->hasRole('super-admin')){
            $productSales = Sales::orderBy('created_at', 'desc')->paginate(10);     
        }
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

            $subtotal = $quantity * $product->selling_price;
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

        // Update jurnal terkait
        $journal = \App\Models\Journal::where('description', 'like', '%Sale #' . $sale->id . '%')->first();
        if ($journal) {
            $journal->update([
                'journal_date' => now(),
                'description' => 'Jurnal penjualan otomatis untuk Sale #' . $sale->id,
                'company_id' => FacadesAuth::user()->company_id ?? 1,
                'branch_id' => FacadesAuth::user()->branch_id ?? 1,
                'created_by' => FacadesAuth::user()->name ?? 'System',
            ]);

            $salesAccount = \App\Models\Account::where('code', '1-101')->first(); // Penjualan
            $cashAccount  = \App\Models\Account::where('code', '1-102')->first(); // Kas

            if (!$salesAccount || !$cashAccount) {
                throw new \Exception("Akun penjualan atau kas tidak ditemukan.");
            }

            // Update journal entries
            \App\Models\JournalEntries::where('journal_id', $journal->id)->delete();

            \App\Models\JournalEntries::create([
                'journal_id' => $journal->id,
                'account_id' => $cashAccount->id,
                'debit' => $sale->total_price,
                'credit' => 0,
                'source_event' => 'SALE',
                'source_ref_id' => $sale->id,
            ]);

            \App\Models\JournalEntries::create([
                'journal_id' => $journal->id,
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $sale->total_price,
                'source_event' => 'SALE',
                'source_ref_id' => $sale->id,
            ]);
        }

        DB::commit();

        return redirect()->route('sales')->with('success', 'Penjualan & jurnal berhasil diupdate!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}



public function edit($id)
{
    $sale = Sales::with(['saleItems.product'])->findOrFail($id);
    $products = Product::select('id', 'product_name', 'product_code', 'stock', 'selling_price')->where("company_id", FacadesAuth::user()->company_id ?? 1)->where("stock", ">", "0")->get();
    
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
    try {
        $sale = Sales::findOrFail($id);
        
        DB::beginTransaction();

        // Ambil semua item penjualan terkait
        $saleItems = SalesItem::where('sale_id', $sale->id)->get();

        foreach ($saleItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                // Tambahkan kembali stok produk sesuai quantity yang terjual
                $product->increment('stock', $item->quantity);
            }
        }

        // Hapus semua item penjualan terkait
        SalesItem::where('sale_id', $sale->id)->delete();

        // Hapus penjualan
        $sale->delete();

        DB::commit();

        return redirect()->route('sales')->with('success', 'Penjualan berhasil dihapus dan stok produk dikembalikan!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}



}
