<x-app-layout>
    <section class="container px-4 mx-auto mt-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Detail Jurnal Entry</h2>
                    <span class="px-3 py-1.5 text-xs font-medium rounded-full 
                        {{ $items->sum('debit') == $items->sum('credit') ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $items->sum('debit') == $items->sum('credit') ? 'Balanced' : 'Unbalanced' }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                    {{ $journal->description ?? 'Journal Entry Transaction' }}
                </p>
                {{-- @if($journal->created_by)
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Dibuat oleh: {{ $journal->created_by }} • {{ $journal->created_at ? $journal->created_at->format('d M Y H:i') : '' }}
                    </p>
                @endif --}}
            </div>

            <div class="flex items-center mt-4 gap-x-3">
                {{-- Uncomment if edit functionality is needed
                <button onclick="openEditModal()" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>Edit Entry</span>
                </button>
                --}}
            </div>
        </div>

        <!-- Journal Summary Cards -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Date Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Jurnal</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $journal->journal_date ? \Carbon\Carbon::parse($journal->journal_date)->format('d M Y') : date('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Debit Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Debit</h3>
                        <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                            Rp {{ number_format($items->sum('debit'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Credit Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kredit</h3>
                        <p class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                            Rp {{ number_format($items->sum('credit'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Entry Count Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg dark:bg-gray-700">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Entry</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $items->count() }} Entry
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        @if($items->where('source_event', '!=', '')->count() > 0)
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Transaksi</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Jenis Transaksi:</span>
                    <div class="mt-1">
                        @php
                            $sourceEvents = $items->pluck('source_event')->unique()->filter();
                        @endphp
                        @foreach($sourceEvents as $event)
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full mr-2 mb-1">
                                {{ $event }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Referensi:</span>
                    <div class="mt-1">
                        @php
                            $sourceRefs = $items->pluck('source_ref_id')->unique()->filter();
                        @endphp
                        @foreach($sourceRefs as $ref)
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ $ref }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                    <div class="mt-1">
                        @if($items->sum('debit') == $items->sum('credit'))
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Seimbang
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Tidak Seimbang
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Journal Entry Table -->
        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        No
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Kode Akun
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Nama Akun
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Jenis Transaksi
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-right rtl:text-left text-gray-500 dark:text-gray-400">
                                        Debit
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-right rtl:text-left text-gray-500 dark:text-gray-400">
                                        Kredit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                @foreach($items as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 {{ $item->source_event && str_contains($item->source_event, 'PEMBALIKAN') ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <span class="text-gray-800 dark:text-white">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold {{ $item->debit > 0 ? 'text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200' : 'text-purple-700 bg-purple-100 dark:bg-purple-900 dark:text-purple-200' }} rounded-full">
                                            {{ $item->account->code ?? $item->account_id }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-gray-900 dark:text-gray-100 font-medium">
                                                {{ $item->account->name ?? 'Account ' . $item->account_id }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->account->type ?? 'Unknown Type' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        @if($item->source_event)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ str_contains($item->source_event, 'PEMBALIKAN') ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                {{ $item->source_event }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-right whitespace-nowrap">
                                        @if($item->debit > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                Rp {{ number_format($item->debit, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-right whitespace-nowrap">
                                        @if($item->credit > 0)
                                            <span class="text-purple-600 dark:text-purple-400">
                                                Rp {{ number_format($item->credit, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                            <!-- Total Footer -->
                            <tfoot class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-sm font-semibold text-right text-gray-800 dark:text-white">
                                        <div class="flex items-center justify-end">
                                            <span class="mr-2">Total:</span>
                                            @if($items->sum('debit') == $items->sum('credit'))
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-right text-green-600 dark:text-green-400 border-t-2 border-gray-300 dark:border-gray-600">
                                        Rp {{ number_format($items->sum('debit'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-right text-purple-600 dark:text-purple-400 border-t-2 border-gray-300 dark:border-gray-600">
                                        Rp {{ number_format($items->sum('credit'), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <!-- Balance Check Row -->
                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-2 text-sm font-medium text-right text-gray-600 dark:text-gray-300">
                                        Selisih:
                                    </td>
                                    <td colspan="2" class="px-4 py-2 text-sm font-bold text-right">
                                        @php $difference = $items->sum('debit') - $items->sum('credit'); @endphp
                                        <span class="{{ $difference == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            Rp {{ number_format(abs($difference), 0, ',', '.') }}
                                            @if($difference == 0)
                                                (Seimbang)
                                            @elseif($difference > 0)
                                                (Debit Lebih)
                                            @else
                                                (Kredit Lebih)
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800 p-6">
            <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Keterangan Jurnal
            </h3>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $journal->description ?? 'Transaksi journal entry dengan total nilai Rp ' . number_format($items->sum('debit'), 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('finance') }}" class="flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
                Kembali ke Daftar Jurnal
            </a>
            
            <button onclick="window.print()" class="flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c0-.621-.504-1.125-1.125-1.125H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c0 .621-.504-1.125-1.125-1.125H6.375a1.125 1.125 0 01-1.125-1.125V3.375z" />
                </svg>
                Print Jurnal
            </button>
        </div>
    </section>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .dark\:bg-gray-800,
            .dark\:bg-gray-900 {
                background: white !important;
                color: black !important;
            }
            
            .shadow {
                box-shadow: none !important;
            }
        }
    </style>
</x-app-layout>