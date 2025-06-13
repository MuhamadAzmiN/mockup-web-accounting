<x-app-layout>
    <section class="container px-4 mx-auto mt-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('procurement') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Purchase Orders
                    </a>
                </div>
                <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Create New Purchase Order</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Create a new purchase order for your inventory</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <form action="{{ route('procurement.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- PO Number -->
                        <div>
                            <label for="po_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                PO Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <input type="text" name="po_number" id="po_number" value="{{ old('po_number', 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(4))) }}" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Enter PO number" required>
                            </div>
                            @error('po_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                      
                        <!-- Order Date -->
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Order Date <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                                    required>
                            </div>
                            @error('order_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Branch -->
                        
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <select name="branch_id" id="branch_id" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                                    required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Amount -->
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Amount
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-sm">Rp</span>
                                </div>
                                <input type="number" name="total_amount" id="total_amount" value="{{ old('total_amount', 0) }}" min="0" step="0.01"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                                    placeholder="0.00" readonly>
                            </div>
                            @error('total_amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Single Product Item Section -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">Product Item</h3>
                        </div>

                        <div id="product-item-container" class="space-y-4">
                            <!-- Product item will be added here -->
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Create Purchase Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Product Item Template (Hidden) -->
    <div id="product-item-template" class="hidden">
        <div class="product-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Product Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Product <span class="text-red-500">*</span>
                    </label>
                    <select name="items[0][product_id]" class="product-select block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-colors duration-200" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">
                                {{ $product->product_name }} ({{ $product->product_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="items[0][quantity]" min="1" value="1" class="quantity block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-colors duration-200" required>
                </div>
                
                <!-- Unit Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Unit Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                        </div>
                        <input type="number" name="items[0][unit_price]" class="unit-price block w-full pl-10 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-colors duration-200" min="0" step="0.01" required>
                    </div>
                </div>
                
                <!-- Subtotal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtotal</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                        </div>
                        <input type="text" class="subtotal block w-full pl-10 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-600 dark:border-gray-500 dark:text-white" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const productItemContainer = document.getElementById('product-item-container');
            const productItemTemplate = document.getElementById('product-item-template');
            const totalAmountInput = document.getElementById('total_amount');


            
            // Add the single product item
            const newItem = productItemTemplate.cloneNode(true);
            newItem.classList.remove('hidden');
            newItem.removeAttribute('id');
            productItemContainer.appendChild(newItem);
            
            // Initialize event listeners for the product item
            initProductItemEvents(newItem);

            const branchSelect = document.getElementById("branch_id");
    const productSelects = document.querySelectorAll(".product-select");

    branchSelect.addEventListener("change", function () {
        const branchId = this.value;
        if (!branchId) return;

        fetch(`/get-products-by-branch/${branchId}`)
            .then(response => response.json())
            .then(data => {
                productSelects.forEach(select => {
                    // Kosongkan dulu isinya
                    select.innerHTML = '<option value="">Select Product</option>';
                    
                    data.forEach(product => {
                        const option = document.createElement("option");
                        option.value = product.id;
                        option.text = `${product.product_name} (${product.product_code})`;
                        option.dataset.price = product.purchase_price;
                        select.appendChild(option);
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    });
            
            function initProductItemEvents(item) {
                const productSelect = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity');
                const unitPriceInput = item.querySelector('.unit-price');
                const subtotalInput = item.querySelector('.subtotal');
                
                // When product is selected, set the unit price
                productSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const price = selectedOption.getAttribute('data-price');
                        unitPriceInput.value = price;
                        calculateSubtotal();
                    } else {
                        unitPriceInput.value = '';
                        subtotalInput.value = '';
                    }
                    updateTotalAmount();
                });
                
                // When quantity or unit price changes, update subtotal
                quantityInput.addEventListener('input', calculateSubtotal);
                unitPriceInput.addEventListener('input', calculateSubtotal);
                
                // Calculate subtotal for this item
                function calculateSubtotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const subtotal = quantity * unitPrice;
                    subtotalInput.value = subtotal.toFixed(2);
                    updateTotalAmount();
                }
            }
            
            // Update total amount
            function updateTotalAmount() {
                const subtotalInput = document.querySelector('.subtotal');
                const total = parseFloat(subtotalInput.value) || 0;
                totalAmountInput.value = total.toFixed(2);
            }
        });
    </script>
</x-app-layout>
