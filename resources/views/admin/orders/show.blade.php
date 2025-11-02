<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">📋 Order #{{ $order->id }}</h1>
                <p class="mt-2 text-gray-600">Order details and management</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Order Information</h3>
                            
                            <!-- Order Status -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-1">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>👨‍🍳 Preparing</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>✅ Ready</option>
                                        <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🎉 Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                    </select>
                                </form>
                            </div>

                            <!-- Order Items -->
                            <div class="mb-4">
                                <h4 class="text-md font-semibold text-gray-900 mb-2">🍣 Order Items</h4>
                                <div class="space-y-2">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <span class="font-medium">{{ $item->product->name }}</span>
                                                <span class="text-sm text-gray-500">x{{ $item->quantity }}</span>
                                            </div>
                                            <span class="font-semibold">${{ number_format($item->total_price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Total -->
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center text-lg font-semibold">
                                    <span>Total:</span>
                                    <span class="text-green-600">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">📍 Delivery Information</h3>
                            <div class="space-y-2">
                                <div><strong>Address:</strong> {{ $order->delivery_address }}</div>
                                <div><strong>Phone:</strong> {{ $order->phone }}</div>
                                @if($order->notes)
                                    <div><strong>Notes:</strong> {{ $order->notes }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Customer</h3>
                            <div class="space-y-2">
                                <div><strong>Name:</strong> {{ $order->user->name }}</div>
                                <div><strong>Email:</strong> {{ $order->user->email }}</div>
                                <div><strong>Phone:</strong> {{ $order->user->phone }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Assignment -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Staff Assignment</h3>
                            <div class="space-y-3">
                                <div>
                                    <strong>👨‍🍳 Chef:</strong>
                                    <span class="ml-2">{{ $order->chef ? $order->chef->name : 'Not assigned' }}</span>
                                </div>
                                <div>
                                    <strong>🚚 Delivery:</strong>
                                    <span class="ml-2">{{ $order->deliveryGuy ? $order->deliveryGuy->name : 'Not assigned' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">⏰ Order Timeline</h3>
                            <div class="space-y-2 text-sm">
                                <div>📅 Created: {{ $order->created_at->format('M j, Y H:i') }}</div>
                                @if($order->preparing_at)
                                    <div>👨‍🍳 Started: {{ $order->preparing_at->format('M j, Y H:i') }}</div>
                                @endif
                                @if($order->ready_at)
                                    <div>✅ Ready: {{ $order->ready_at->format('M j, Y H:i') }}</div>
                                @endif
                                @if($order->out_for_delivery_at)
                                    <div>🚚 Out for delivery: {{ $order->out_for_delivery_at->format('M j, Y H:i') }}</div>
                                @endif
                                @if($order->delivered_at)
                                    <div>🎉 Delivered: {{ $order->delivered_at->format('M j, Y H:i') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Tracking -->
                    @if($order->status == 'out_for_delivery' && $order->deliveryGuy)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">🚚 Delivery Tracking</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('orders.tracking', $order) }}" target="_blank" 
                                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg">
                                        📍 View Live Tracking
                                    </a>
                                    <a href="{{ route('delivery.orders.navigation', $order) }}" target="_blank" 
                                       class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded-lg">
                                        🗺️ Driver Navigation
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


