<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Dashboard Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Keuangan</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Overview lengkap keuangan dan aktivitas bisnis Anda</p>
            </div>
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Export button -->
                <button class="btn bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-100">
                    <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L4 4h2v5h4V4h2L8 0zM2 12v2h12v-2H2z"/>
                    </svg>
                    <span>Export</span>
                </button>
                <!-- Filter by date -->
                <select class="form-select text-sm">
                    <option>Bulan Ini</option>
                    <option>3 Bulan Terakhir</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
        </div>

        <!-- Quick Stats Cards -->


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Pemasukan -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pemasukan</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Rp {{ number_format($financialSummary['total_income'], 0, ',', '.') }}
            </p>
            <p class="text-sm {{ $financialSummary['income_percentage'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} flex items-center mt-1">
                @if ($financialSummary['income_percentage'] >= 0)
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    +{{ $financialSummary['income_percentage'] }}% dari bulan lalu
                @else
                    <svg class="w-4 h-4 mr-1 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $financialSummary['income_percentage'] }}% dari bulan lalu
                @endif
            </p>
        </div>
        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
    </div>
</div>


    <!-- Produk Tersedia -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Produk Tersedia</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $goodsSold['total_quantity'] }}</p>
                <p class="text-sm text-blue-600 dark:text-blue-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                   {{ $goodsSold['sold_percentage'] }}% dari bulan lalu
                </p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($getTotalExpenditure['total_expenditure'], 0, ',', '.') }}</p>
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $getTotalExpenditure['expenditure_percentage'] }}% dari bulan lalu
                </p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Produk Dipesan -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Produk Dipesan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($getTotalProductPurchased['total_inventory_product_purchased'], 0) }}</p>
                <p class="text-sm text-purple-600 dark:text-purple-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $getTotalProductPurchased['inventory_purchase_percentage'] }}% dari bulan lalu
                </p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp 150.000.000</p>
                        <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +12.5% dari bulan lalu
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp 85.000.000</p>
                        <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +8.2% dari bulan lalu
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Laba Bersih</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp 65.000.000</p>
                        <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +18.9% dari bulan lalu
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Products Sold -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Barang Terjual</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">2,457</p>
                        <p class="text-sm text-blue-600 dark:text-blue-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +24.3% dari bulan lalu
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>



        <!-- Main Content Grid -->
        <div class="grid grid-cols-12 gap-6">
            <!-- 1. NERACA (Balance Sheet) -->
            <div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Neraca Keuangan</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Posisi keuangan per {{ date('d M Y') }}</p>
                </header>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Assets -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">ASET</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Kas & Bank</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 125.000.000</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Piutang Dagang</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 45.000.000</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Persediaan</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 78.000.000</span>
                                </div>
                                <div class="flex justify-between items-center py-2 font-medium">
                                    <span class="text-sm text-gray-800 dark:text-gray-200">Total Aset</span>
                                    <span class="text-sm text-blue-600 dark:text-blue-400">Rp 248.000.000</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Liabilities -->
                        <div class="pt-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">KEWAJIBAN</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hutang Dagang</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 32.000.000</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hutang Bank</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 85.000.000</span>
                                </div>
                                <div class="flex justify-between items-center py-2 font-medium">
                                    <span class="text-sm text-gray-800 dark:text-gray-200">Total Kewajiban</span>
                                    <span class="text-sm text-red-600 dark:text-red-400">Rp 117.000.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. LAPORAN LABA/RUGI (Profit & Loss) -->
            <div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Laporan Laba/Rugi</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Periode {{ date('M Y') }}</p>
                </header>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pendapatan Penjualan</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">Rp 150.000.000</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Harga Pokok Penjualan</span>
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">(Rp 60.000.000)</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 font-medium">
                                <span class="text-sm text-gray-800 dark:text-gray-200">Laba Kotor</span>
                                <span class="text-sm text-blue-600 dark:text-blue-400">Rp 90.000.000</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Biaya Operasional</span>
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">(Rp 25.000.000)</span>
                            </div>
                            <div class="flex justify-between items-center py-2 font-semibold bg-green-50 dark:bg-green-900/20 px-3 rounded-lg">
                                <span class="text-sm text-gray-800 dark:text-gray-200">Laba Bersih</span>
                                <span class="text-sm text-green-600 dark:text-green-400">Rp 65.000.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. LAPORAN PAJAK (Tax Report) -->
            <div class="col-span-full lg:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Laporan Pajak</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status pajak bulan ini</p>
                </header>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Jatuh Tempo: 20 Des 2024</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">PPh Badan</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 6.500.000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">PPN</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 15.000.000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">PPh 21</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 3.200.000</span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center font-semibold">
                                    <span class="text-sm text-gray-800 dark:text-gray-200">Total Pajak</span>
                                    <span class="text-sm text-orange-600 dark:text-orange-400">Rp 24.700.000</span>
                                </div>
                            </div>
                        </div>
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                            Bayar Pajak
                        </button>
                    </div>
                </div>
            </div>

            <!-- 4. AKTIVITAS MARKETING -->
            <div class="col-span-full lg:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Aktivitas Marketing</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Performance campaign bulan ini</p>
                </header>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Campaign Stats -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Campaign Aktif</p>
                                <p class="text-lg font-bold text-blue-700 dark:text-blue-300">12</p>
                            </div>
                            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <p class="text-xs text-green-600 dark:text-green-400 font-medium">Conversion Rate</p>
                                <p class="text-lg font-bold text-green-700 dark:text-green-300">4.7%</p>
                            </div>
                        </div>

                        <!-- Marketing Channels -->
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Top Channels</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Social Media</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">1.2K leads</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Google Ads</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">890 leads</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Email Marketing</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">567 leads</span>
                                </div>
                            </div>
                        </div>

                        <!-- Marketing Budget -->
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Budget Terpakai</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Rp 12.5M / Rp 20M</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 62.5%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. KODE MARKETING & 6. JUMLAH BARANG TERJUAL -->
            <div class="col-span-full lg:col-span-12 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Marketing & Sales</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Kode marketing dan penjualan</p>
                </header>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Marketing Codes -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kode Marketing Aktif</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <span class="text-sm font-mono font-medium text-gray-900 dark:text-gray-100">DISC25</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Diskon 25%</p>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded">143 digunakan</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <span class="text-sm font-mono font-medium text-gray-900 dark:text-gray-100">NEWBIE10</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Member baru 10%</p>
                                    </div>
                                    <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-1 rounded">89 digunakan</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <span class="text-sm font-mono font-medium text-gray-900 dark:text-gray-100">FLASH50</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Flash sale 50%</p>
                                    </div>
                                    <span class="text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-1 rounded">267 digunakan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Top Selling Products -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Produk Terlaris</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-bold">1</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Laptop Gaming X1</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">456 unit terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">Rp 48.5M</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-bold">2</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Smartphone Pro Max</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">334 unit terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">Rp 42.1M</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-bold">3</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Headset Wireless</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">789 unit terjual</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">Rp 23.6M</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Summary -->
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <p class="text-xl font-bold text-blue-700 dark:text-blue-300">2,457</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">Total Unit</p>
                                </div>
                                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <p class="text-xl font-bold text-green-700 dark:text-green-300">Rp 150M</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section - Revenue Trend -->
            <div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tren Pendapatan & Pengeluaran</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">6 bulan terakhir</p>
                </header>
                <div class="p-6">
                    <!-- Placeholder for chart - you can integrate with Chart.js or similar -->
                    <div class="h-64 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-700 dark:to-gray-600 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Grafik Tren Pendapatan</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Integrate dengan Chart.js untuk data real-time</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-span-full xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Transaksi Terbaru</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Aktivitas hari ini</p>
                </header>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Penjualan Online</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">10:30 AM</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">+Rp 2.450.000</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Pembelian Inventory</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">09:15 AM</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">-Rp 1.200.000</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Pembayaran Marketing</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">08:45 AM</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">-Rp 850.000</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Pembayaran Piutang</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">07:30 AM</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">+Rp 3.200.000</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button class="w-full text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium py-2 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            Lihat Semua Transaksi
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>