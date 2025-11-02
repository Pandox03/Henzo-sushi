<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ Add New Product
            </h2>
            <a href="{{ route('admin.products') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                ← Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (MAD)</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Discount Settings (Optional)</h3>
                            
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_discount" value="1" {{ old('has_discount') ? 'checked' : '' }} 
                                        onchange="toggleDiscountFields()" class="mr-2">
                                    <span class="text-sm font-medium text-gray-700">Enable Discount</span>
                                </label>
                            </div>

                            <div id="discount-fields" class="space-y-4 {{ old('has_discount') ? '' : 'hidden' }}">
                                <div>
                                    <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                                    <select name="discount_type" id="discount_type" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (MAD)</option>
                                    </select>
                                    @error('discount_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                                    <input type="number" name="discount_value" id="discount_value" 
                                        value="{{ old('discount_value') }}" step="0.01" min="0.01"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                        placeholder="e.g., 15 for 15% or 10.00 for 10 MAD">
                                    @error('discount_value')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount_expires_at" class="block text-sm font-medium text-gray-700">Discount Expires At (Optional)</label>
                                    <input type="date" name="discount_expires_at" id="discount_expires_at" 
                                        value="{{ old('discount_expires_at') }}"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    @error('discount_expires_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Leave empty for no expiration</p>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <p class="mt-1 text-sm text-gray-500">Upload a high-quality image of your product (max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preparation Time -->
                        <div>
                            <label for="preparation_time" class="block text-sm font-medium text-gray-700">Preparation Time (minutes)</label>
                            <input type="number" name="preparation_time" id="preparation_time" value="{{ old('preparation_time', 15) }}" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('preparation_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Availability -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
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
                                Add Product
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
</x-app-layout>

