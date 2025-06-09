<x-app-layout>
    <section class="container px-4 mx-auto mt-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Journal Entry Details</h2>
                   
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">{{ $journal->description ?? 'Journal Entry Transaction' }}</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3">
              {{-- <button onclick="openEditModal()" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>Edit Entry</span>
                </button> --}}
            </div>
        </div>

        <!-- Journal Info Card -->
        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $journal->journal_date ? \Carbon\Carbon::parse($journal->journal_date)->format('d M Y') : date('d M Y') }}
                    </p>
                </div>
              
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Source Event</h3>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $items->first()->source_event ?? '-' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</h3>
                    <p class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">
                        Rp {{ number_format($items->sum('debit'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

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
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Kode Akun
                                    </th>
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Nama Akun
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
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <span class="text-gray-800 dark:text-white">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold {{ $item->debit > 0 ? 'text-blue-600 bg-blue-100 dark:bg-blue-900 dark:text-blue-200' : 'text-purple-600 bg-purple-100 dark:bg-purple-900 dark:text-purple-200' }} rounded-full">
                                            {{ $item->account_id }}
                                        </span>
                                    </td>
                                    <td class="px-12 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-gray-700 dark:text-gray-200 font-medium">
                                                {{ $item->account->name ?? 'Account ' . $item->account_id }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->account->type ?? 'Account Type' }}
                                            </p>
                                        </div>
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
                                            <span class="text-blue-600 dark:text-blue-400">
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
                                    <td colspan="3" class="px-4 py-4 text-sm font-semibold text-right text-gray-800 dark:text-white">
                                        Total:
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-right text-green-600 dark:text-green-400 border-t-2 border-gray-300 dark:border-gray-600">
                                        Rp {{ number_format($items->sum('debit'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-right text-blue-600 dark:text-blue-400 border-t-2 border-gray-300 dark:border-gray-600">
                                        Rp {{ number_format($items->sum('credit'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Journal Description & Source Reference -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Keterangan</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $journal->description ?? 'Transaksi journal entry dengan total nilai Rp ' . number_format($items->sum('debit'), 0, ',', '.') }}
                </p>
            </div>

            @if($items->first()->source_ref_id)
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Source Reference</h3>
                <div class="space-y-2">
                   
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Event Type:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $items->first()->source_event }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('finance') }}" class="flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            
            {{-- <button onclick="window.print()" class="flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c0-.621-.504-1.125-1.125-1.125H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c0 .621-.504-1.125-1.125-1.125H6.375a1.125 1.125 0 01-1.125-1.125V3.375z" />
                </svg>
                Print Journal
            </button>

            @if($items->first()->source_event == 'PENJUALAN_TUNAI' && $items->first()->source_ref_id)
            <a href="" class="flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Lihat Invoice
            </a>
            @endif --}}
        </div>
    </section>
    <!-- Edit Journal Entry Modal -->
<div id="editJournalModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/70 backdrop-blur-sm">
    <div class="w-full max-w-2xl p-6 mx-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Journal Entry</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content -->
        <form action="" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <!-- Date Input -->
                <div>
                    <label for="journal_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                    <input type="date" id="journal_date" name="journal_date" value="{{ $journal->journal_date }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <!-- Description Input -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $journal->description }}</textarea>
                </div>
                
                <!-- Journal Items Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Akun</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Debit</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Kredit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($items as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    <select name="account_id[]" class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $item->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->code }} - {{ $account->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="debit[]" value="{{ $item->debit }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="credit[]" value="{{ $item->credit }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white dark:border-gray-600">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal functions
    function openEditModal() {
        document.getElementById('editJournalModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editJournalModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editJournalModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }
</script>

</x-app-layout>