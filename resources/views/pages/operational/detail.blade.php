<x-app-layout>
    <section class="container px-4 mx-auto mt-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('products') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Products Operational
                    </a>
                </div>
                <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Product Operational Details</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">View detailed information about this product operational</p>
            </div>
            <div class="flex items-center mt-4 sm:mt-0">
                {{-- <a href="{{ route('operational.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500 transition-colors duration-200 mr-3">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button onclick="openDeleteModal('{{ $product->id }}', '{{ $product->product_name }}')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button> --}}
            </div>
        </div>

        <!-- Product Detail Section -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Left Column -->
                        <div class="md:col-span-2">
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                                    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Product Code</label>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->product_code }}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">External Product Code</label>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->external_product_code ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Product Name</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->product_name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock & Price Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Stock & Pricing</h3>
                                    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Quantity</label>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->stock }}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Selling Price</label>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Additional Information</h3>
                                    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $product->information ?? 'No additional information provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column (Optional for image or other details) -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 h-full">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Product Image</h3>
                                <div class="flex items-center justify-center bg-white dark:bg-gray-600 rounded-lg p-4 h-64">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <div class="text-center text-gray-400 dark:text-gray-300">
                                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm">No image available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Modal (Same as in your edit page) -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-hidden="true">
        <!-- Backdrop dengan transparansi hitam -->
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.419)] transition-opacity duration-300" onclick="closeDeleteModal()"></div>
        
        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Modal Content -->
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all duration-300 sm:w-full sm:max-w-lg dark:bg-gray-800 scale-95 opacity-0" id="modalContent">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Delete Product
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                    Are you sure you want to delete "<span id="productName" class="font-medium"></span>"? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                    <form id="deleteForm" method="POST" class="sm:ml-3 sm:w-auto sm:flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transition-colors duration-200 dark:bg-red-500 dark:hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(productId, productName) {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            
            document.getElementById('productName').textContent = productName;
            document.getElementById('deleteForm').action = `/products/${productId}`;
            
            // Show modal and backdrop
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            
            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            
            // Trigger close animation
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }, 300);
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('deleteModal');
                if (!modal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            }
        });
    </script>
</x-app-layout>