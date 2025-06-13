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

    // Ambil semua data
    $financialSummary = $this->getFinancialSummary();
    $getTotalExpenditure = $this->getTotalExpenditure();
    $goodsSold = $this->getGoodsSold();
    $getTotalProductPurchased = $this->getTotalProductPurchased();

    return view('pages.dashboard.dashboard', compact(
        'dataFeed',
        'financialSummary',
        'getTotalExpenditure',
        'goodsSold',
        'getTotalProductPurchased'
    ));
}

    
   private function getFinancialSummary()
{
    $currentDate = Carbon::now();

    // Bulan ini
    $totalIncome = DB::table('vw_profit_and_loss')
        ->where('account_type', 'Pendapatan')
        ->whereMonth('journal_date', $currentDate->month)
        ->whereYear('journal_date', $currentDate->year)
        ->sum('net_value') ?? 0;

    // Bulan lalu
    $previousMonth = $currentDate->copy()->subMonth();
    $previousMonthIncome = DB::table('vw_profit_and_loss')
        ->where('account_type', 'Pendapatan')
        ->whereMonth('journal_date', $previousMonth->month)
        ->whereYear('journal_date', $previousMonth->year)
        ->sum('net_value') ?? 0;

    // Hitung persentase pertumbuhan pemasukan
    $incomePercentage = 0;
    if ($previousMonthIncome > 0) {
        $incomePercentage = round((($totalIncome - $previousMonthIncome) / $previousMonthIncome) * 100, 2);
    } elseif ($totalIncome > 0) {
        $incomePercentage = 100;
    }

    return [
        'total_income' => $totalIncome,
        'income_percentage' => $incomePercentage,
        'total_expense' => 0,
        'net_profit' => 0,
        'expense_percentage' => 0
    ];
}



   private function getTotalExpenditure()
{
    $companyId = Auth::user()->company_id;
    $now = Carbon::now();

    // Total pengeluaran bulan ini
    $currentMonthTotal = DB::table('purchase_orders')
        ->join('products', 'purchase_orders.product_id', '=', 'products.id')
        ->where('purchase_orders.company_id', $companyId)
        ->where('products.type', 'operational') // hanya produk operational
        ->whereNull('purchase_orders.deleted_at')
        ->whereMonth('purchase_orders.created_at', $now->month)
        ->whereYear('purchase_orders.created_at', $now->year)
        ->sum('purchase_orders.total_amount');

    // Total pengeluaran bulan sebelumnya
    $previousMonthTotal = DB::table('purchase_orders')
        ->join('products', 'purchase_orders.product_id', '=', 'products.id')
        ->where('purchase_orders.company_id', $companyId)
        ->where('products.type', 'operational')
        ->whereNull('purchase_orders.deleted_at')
        ->whereMonth('purchase_orders.created_at', $now->subMonth()->month)
        ->whereYear('purchase_orders.created_at', $now->year)
        ->sum('purchase_orders.total_amount');

    // Hitung persentase perubahan
    $percentage = 0;
    if ($previousMonthTotal > 0) {
        $percentage = round((($currentMonthTotal - $previousMonthTotal) / $previousMonthTotal) * 100, 2);
    } elseif ($currentMonthTotal > 0) {
        $percentage = 100;
    }

    return [
        'total_expenditure' => $currentMonthTotal,
        'expenditure_percentage' => $percentage,
    ];
}




  private function getGoodsSold()
{
    $companyId = Auth::user()->company_id;
    $now = Carbon::now();

    $currentMonth = DB::table('sale_items')
        ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
        ->where('sales.company_id', $companyId)
        ->whereMonth('sales.created_at', $now->month)
        ->whereYear('sales.created_at', $now->year)
        ->sum('sale_items.quantity');

    $previousMonth = DB::table('sale_items')
        ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
        ->where('sales.company_id', $companyId)
        ->whereMonth('sales.created_at', $now->copy()->subMonth()->month)
        ->whereYear('sales.created_at', $now->copy()->subMonth()->year)
        ->sum('sale_items.quantity');

    $percentage = 0;
    if ($previousMonth > 0) {
        $percentage = round((($currentMonth - $previousMonth) / $previousMonth) * 100, 2);
    } elseif ($currentMonth > 0) {
        $percentage = 100;
    }

    return [
        'total_quantity' => $currentMonth ?? 0,
        'sold_percentage' => $percentage,
    ];
}



private function getTotalProductPurchased() 
{
    $companyId = Auth::user()->company_id;
    $now = Carbon::now();

    $currentCount = DB::table('purchase_orders')
        ->join('products', 'purchase_orders.product_id', '=', 'products.id')
        ->where('purchase_orders.company_id', $companyId)
        ->where('products.type', 'inventory')
        ->whereNull('purchase_orders.deleted_at')
        ->whereMonth('purchase_orders.created_at', $now->month)
        ->whereYear('purchase_orders.created_at', $now->year)
        ->count();

    $previousCount = DB::table('purchase_orders')
        ->join('products', 'purchase_orders.product_id', '=', 'products.id')
        ->where('purchase_orders.company_id', $companyId)
        ->where('products.type', 'inventory')
        ->whereNull('purchase_orders.deleted_at')
        ->whereMonth('purchase_orders.created_at', $now->copy()->subMonth()->month)
        ->whereYear('purchase_orders.created_at', $now->copy()->subMonth()->year)
        ->count();

    $percentage = 0;
    if ($previousCount > 0) {
        $percentage = round((($currentCount - $previousCount) / $previousCount) * 100, 2);
    } elseif ($currentCount > 0) {
        $percentage = 100;
    }

    return [
        'total_inventory_product_purchased' => $currentCount ?? 0,
        'inventory_purchase_percentage' => $percentage,
    ];
}










}
