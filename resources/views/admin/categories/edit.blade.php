<x-admin-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">✏️ Edit Category</h1>
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">{{ old('description', $category->description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            @if($category->image)
                                <div class="mb-2"><img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded"></div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="flex items-end">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.categories') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">Cancel</a>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


