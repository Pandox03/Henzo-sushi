<x-admin-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">✏️ Edit Product</h1>
                <p class="mt-2 text-gray-600">Update product information and pricing</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (MAD)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Discount Settings</h3>
                            
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_discount" value="1" {{ old('has_discount', $product->has_discount) ? 'checked' : '' }} 
                                        onchange="toggleDiscountFields()" class="mr-2">
                                    <span class="text-sm font-medium text-gray-700">Enable Discount</span>
                                </label>
                            </div>

                            <div id="discount-fields" class="space-y-4 {{ old('has_discount', $product->has_discount) ? '' : 'hidden' }}">
                                <div>
                                    <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                                    <select name="discount_type" id="discount_type" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                        <option value="percentage" {{ old('discount_type', $product->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="fixed" {{ old('discount_type', $product->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (MAD)</option>
                                    </select>
                                    @error('discount_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                                    <input type="number" name="discount_value" id="discount_value" 
                                        value="{{ old('discount_value', $product->discount_value) }}" step="0.01" min="0.01"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                        placeholder="e.g., 15 for 15% or 10.00 for 10 MAD">
                                    @error('discount_value')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount_expires_at" class="block text-sm font-medium text-gray-700">Discount Expires At (Optional)</label>
                                    <input type="date" name="discount_expires_at" id="discount_expires_at" 
                                        value="{{ old('discount_expires_at', $product->discount_expires_at ? $product->discount_expires_at->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    @error('discount_expires_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Leave empty for no expiration</p>
                                </div>

                                @if($product->has_discount && $product->isDiscountValid())
                                    <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                        <p class="text-sm text-green-800">
                                            <strong>Current Discount:</strong> 
                                            @if($product->discount_type === 'percentage')
                                                {{ $product->discount_value }}% off = 
                                            @else
                                                {{ number_format($product->discount_value, 2) }} MAD off = 
                                            @endif
                                            <strong>{{ number_format($product->discounted_price, 2) }} MAD</strong> 
                                            (was {{ number_format($product->price, 2) }} MAD)
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($product->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Image</label>
                                <div class="mt-1">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            </div>
                        @endif

                        <!-- New Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">New Product Image (optional)</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <p class="mt-1 text-sm text-gray-500">Upload a new image to replace the current one</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Availability -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                    Available for ordering
                                </label>
                            </div>
                            @error('is_available')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.products') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDiscountFields() {
            const checkbox = document.querySelector('input[name="has_discount"]');
            const fields = document.getElementById('discount-fields');
            
            if (checkbox.checked) {
                fields.classList.remove('hidden');
                // Make discount fields required
                document.getElementById('discount_type').required = true;
                document.getElementById('discount_value').required = true;
            } else {
                fields.classList.add('hidden');
                // Remove required attribute
                document.getElementById('discount_type').required = false;
                document.getElementById('discount_value').required = false;
            }
        }
    </script>
</x-admin-layout>

