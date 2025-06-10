<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\PermissionMatrixController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductProcurementController;
use App\Http\Controllers\SalesController;
use Spatie\Permission\Contracts\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');
    Route::get('/roles', [PermissionMatrixController::class, 'index'])->name('roles');
    Route::post('/permissions/matrix', [PermissionMatrixController::class, 'update'])->name('permissions.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');
    Route::get('/ecommerce/shop', function () {
        return view('pages/ecommerce/shop');
    })->name('shop');    
    

    // Finance Routes
    Route::get("/finance", [FinanceController::class, 'index'])->name('finance');
    Route::get("/finance/details/{id}", [FinanceController::class, 'details'])->name('finance.details');


    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


    // Product Procurement Routes
    Route::get('/procurement', [ProductProcurementController::class, 'index'])->name('procurement');
    Route::get('/procurement/create', [ProductProcurementController::class, 'create'])->name('procurement.create');
    Route::post('/procurement/store', [ProductProcurementController::class, 'store'])->name('procurement.store');
    Route::delete('/procurement/{purchaseOrder}', [ProductProcurementController::class, 'destroy'])->name('procurement.destroy');
    Route::get('/get-products-by-branch/{branchId}', [ProductProcurementController::class, 'getByBranch']);



    // Product Sales
    Route::get('/sales', [SalesController::class, 'index'])->name('sales');
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{productSale}', [SalesController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/{productSale}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('/sales/{productSale}/edit', [SalesController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{productSale}', [SalesController::class, 'update'])->name('sales.update');


    // Product Operational
    Route::get('/operational', [OperationalController::class, 'index'])->name('operational');
    Route::get('/operational/create', [OperationalController::class, 'create'])->name('operational.create');
    Route::post('/operational/store', [OperationalController::class, 'store'])->name('operational.store');
    Route::get('/operational/{id}', [OperationalController::class, 'show'])->name('operational.show');
    Route::delete('/operational/{id}', [OperationalController::class, 'destroy'])->name('operational.destroy');
    Route::get('/operational/{id}/edit', [OperationalController::class, 'edit'])->name('operational.edit');
    Route::put('/operational/{id}', [OperationalController::class, 'update'])->name('operational.update');

});



