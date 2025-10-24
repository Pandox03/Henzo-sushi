<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🍣 Menu Management</h1>
                    <p class="mt-2 text-gray-600">Manage your sushi menu and products</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    ➕ Add New Product
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.products') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or description"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <input type="text" name="category" value="{{ request('category') }}" placeholder="e.g., Sushi"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Availability</label>
                            <select name="availability" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All</option>
                                <option value="available" {{ request('availability')==='available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ request('availability')==='unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex items-center justify-end">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mr-2">Filter</button>
                            <a href="{{ route('admin.products') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Product Image -->
                            <div class="aspect-w-16 aspect-h-9 mb-4">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-4xl">🍣</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $product->description }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">{{ $product->category }}</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                @if($product->is_available)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ❌ Unavailable
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
