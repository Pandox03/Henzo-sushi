<x-admin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">⚙️ System Settings</h1>
                <p class="mt-2 text-gray-600">Configure your Henzo Sushi restaurant settings</p>
            </div>

            <div class="space-y-6">
                <!-- Restaurant Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🏪 Restaurant Information</h3>
                        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="restaurant_name" class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                                    <input type="text" name="restaurant_name" id="restaurant_name" value="Henzo Sushi"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="restaurant_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" name="restaurant_phone" id="restaurant_phone" value="+212 6XX XXX XXX"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div>
                                <label for="restaurant_address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="restaurant_address" id="restaurant_address" rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">Casablanca, Morocco</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    Save Restaurant Info
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delivery Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🚚 Delivery Settings</h3>
                        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="delivery_fee" class="block text-sm font-medium text-gray-700">Delivery Fee ($)</label>
                                    <input type="number" name="delivery_fee" id="delivery_fee" value="0" step="0.01" min="0"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="delivery_radius" class="block text-sm font-medium text-gray-700">Delivery Radius (km)</label>
                                    <input type="number" name="delivery_radius" id="delivery_radius" value="10" min="1"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    Save Delivery Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 System Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Application Stats</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div>Laravel Version: {{ app()->version() }}</div>
                                    <div>PHP Version: {{ PHP_VERSION }}</div>
                                    <div>Environment: {{ app()->environment() }}</div>
                                    <div>Debug Mode: {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Database Stats</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div>Total Users: {{ \App\Models\User::count() }}</div>
                                    <div>Total Orders: {{ \App\Models\Order::count() }}</div>
                                    <div>Total Products: {{ \App\Models\Product::count() }}</div>
                                    <div>Database: {{ config('database.default') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center">
                                <div class="text-2xl mb-2">👥</div>
                                <div class="font-semibold">Add User</div>
                            </a>
                            <a href="{{ route('admin.products.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center">
                                <div class="text-2xl mb-2">🍣</div>
                                <div class="font-semibold">Add Product</div>
                            </a>
                            <a href="{{ route('admin.orders') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center">
                                <div class="text-2xl mb-2">📋</div>
                                <div class="font-semibold">View Orders</div>
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center">
                                <div class="text-2xl mb-2">📊</div>
                                <div class="font-semibold">Dashboard</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

