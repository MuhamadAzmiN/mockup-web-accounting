<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Traits\AuthorizationChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use AuthorizationChecker;
    
    public function index()
    {
        // Cek authorization user untuk akses dashboard
        $this->checkAuthorization(Auth::user(), ['manage_dashboard']);
        
        // Inisialisasi data feed (jika diperlukan di view)
        $dataFeed = new DataFeed();
        
        // Ambil ringkasan finansial
        $financialSummary = $this->getFinancialSummary();
        $getTotalExpenditure = $this->getTotalExpenditure();
        $goodsSold = $this->getGoodsSold();
        $getTotalProductPurchased = $this->getTotalProductPurchased();




        
        // Render view dengan data yang sudah dikompak
        return view('pages.dashboard.dashboard', compact('dataFeed', 'financialSummary', 'getTotalExpenditure', 'goodsSold', 'getTotalProductPurchased'));
    }
    
    private function getFinancialSummary()
    {
        $currentDate = Carbon::now();

        // Nama kolom tanggal yang valid di view vw_profit_and_loss

        // Ambil total pemasukan bulan ini
        $totalIncome = DB::table('vw_profit_and_loss')
            ->where('account_type', 'Pendapatan')
            ->sum('net_value') ?? 0;

        // Ambil total pemasukan bulan sebelumnya
        $previousMonthIncome = DB::table('vw_profit_and_loss')
            ->where('account_type', 'Pendapatan')

            ->sum('net_value') ?? 0;

        // Hitung persentase pertumbuhan pemasukan
        $incomePercentage = 0;
        if ($previousMonthIncome > 0) {
            $incomePercentage = round((($totalIncome - $previousMonthIncome) / $previousMonthIncome) * 100, 2);
        } elseif ($totalIncome > 0) {
            $incomePercentage = 100;
        }

        // Return data ringkasan finansial
        return [
            'total_income' => $totalIncome,
            'income_percentage' => $incomePercentage,
            // Placeholder untuk pengeluaran atau profit jika diperlukan nanti
            'total_expense' => 0,
            'net_profit' => 0,
            'expense_percentage' => 0
        ];
    }


    private function getTotalExpenditure()
    {
        $companyId = Auth::user()->company_id;

        $totalProductPurchased = DB::table('purchase_orders')
            ->join('products', 'purchase_orders.product_id', '=', 'products.id')
            ->where('purchase_orders.company_id', $companyId)
            ->where('products.type', 'operational') // hanya produk operational
            ->where('purchase_orders.status', 'completed') // hanya PO yang completed
            ->sum('purchase_orders.total_amount') ?? 0;

        return [
            'total_product_purchased' => $totalProductPurchased,
        ];
    }



   private function getGoodsSold()
{
    $companyId = Auth::user()->company_id;

    $detailTotalItems = DB::table('sale_items')
        ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
        ->join('products', 'sale_items.product_id', '=', 'products.id') // Join ke tabel products
        ->where('sales.company_id', $companyId)
        ->where('products.type', 'inventory') // Filter hanya yang type = inventory
        ->select(DB::raw('SUM(sale_items.quantity) as total_quantity'))
        ->first();

    return [
        'total_quantity' => $detailTotalItems->total_quantity ?? 0,
    ];
}



private function getTotalProductPurchased() 
{
    $companyId = Auth::user()->company_id;

    $totalOperationalProducts = DB::table('purchase_orders')
        ->join('products', 'purchase_orders.product_id', '=', 'products.id')
        ->where('purchase_orders.company_id', $companyId)
        ->where('products.type', 'operational') // hanya tipe 'operational'
        ->count(); // hitung jumlah data (baris)

    return [
        'total_operational_product_purchased' => $totalOperationalProducts,
    ];
}










}
