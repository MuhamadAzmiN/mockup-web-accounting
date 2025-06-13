<x-app-layout>
    <section class="container px-4 mx-auto mt-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('sales') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Sales
                    </a>
                </div>
                <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Edit New Sale</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Edit a new sales record</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="mt-8">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
       <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="date" name="date" id="date" value="{{ old('date', $sale->date) }}" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200">
                    </div>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Price (Read Only - Auto Calculated) -->
                <div>
                    <label for="total_price_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Total Price <span class="text-gray-400">(Auto Calculated)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm">Rp</span>
                        </div>
                        <input type="text" id="total_price_display" value="Rp {{ number_format($sale->total_price, 0, ',', '.') }}" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 cursor-not-allowed"
                            readonly>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total will be calculated automatically based on products and quantities</p>
                </div>

                <!-- Current Items Count -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Items
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8-4 4-4-4m0 0L9 9l4-4" />
                            </svg>
                        </div>
                        <input type="text" value="{{ $sale->saleItems->count() }} Items" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 cursor-not-allowed"
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Current Sale Items Display -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Current Sale Items:</h4>
                <div class="space-y-2">
                    @foreach($sale->saleItems as $item)
                        <div class="flex justify-between items-center text-sm bg-white dark:bg-gray-700 p-3 rounded border">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $item->product->product_name }}</span>
                                <span class="text-gray-500 dark:text-gray-400">- {{ $item->product->branch->name ?? 'No Branch' }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-gray-900 dark:text-white">Qty: {{ $item->quantity }}</div>
                                <div class="text-gray-600 dark:text-gray-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Selection -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Update Sale Items</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Product <span class="text-red-500">*</span>
                        </label>
                        <select name="products[0][id]" id="product_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200">
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-price="{{ $product->selling_price }}"
                                    {{ old('products.0.id', $sale->saleItems->first()->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                    {{ $product->product_name }} - Rp{{ number_format($product->selling_price, 0, ',', '.') }} 
                                    (Stock: {{ $product->stock }}) - {{ $product->branch->name ?? 'No Branch' }}
                                </option>
                            @endforeach
                        </select>
                        @error('products.0.id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="products[0][quantity]" id="quantity" min="1" 
                            value="{{ old('products.0.quantity', $sale->saleItems->first()->quantity ?? 1) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                            placeholder="Enter quantity">
                        @error('products.0.quantity')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price Preview -->
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-blue-700 dark:text-blue-300">Estimated New Total:</span>
                        <span id="price_preview" class="font-medium text-blue-900 dark:text-blue-100">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notes
                </label>
                <div class="relative">
                    <textarea name="notes" id="notes" rows="4" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200 resize-none"
                        placeholder="Enter any additional notes about this sale...">{{ old('notes', $sale->notes) }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning Notice -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Update Notice
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Stock will be restored for current items, then deducted for new items</li>
                                <li>Previous journal entries will be reversed and new ones will be created</li>
                                <li>This action cannot be undone</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('sales') }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Update Sale
                </button>
            </div>
    </form>
    </div>
</div>


    </section>

    <script>
        // Set default date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
        });

        // Format price input
        document.getElementById('total_price').addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            this.value = value;
        });
        document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const pricePreview = document.getElementById('price_preview');

    function updatePricePreview() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (selectedOption && selectedOption.dataset.price && quantity > 0) {
            const price = parseFloat(selectedOption.dataset.price);
            const total = price * quantity;
            pricePreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        } else {
            pricePreview.textContent = 'Rp 0';
        }
    }

    productSelect.addEventListener('change', updatePricePreview);
    quantityInput.addEventListener('input', updatePricePreview);

    // Initial calculation
    updatePricePreview();
});
    </script>
</x-app-layout>