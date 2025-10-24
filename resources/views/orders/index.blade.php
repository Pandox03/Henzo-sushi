<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="mt-2 text-gray-600">Track your order history and status</p>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All</option>
                                @foreach(['pending','accepted','preparing','ready','out_for_delivery','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3 flex items-end">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md mr-2">Filter</button>
                            <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Order Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                        @elseif($order->status === 'ready') bg-green-100 text-green-800
                                        @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    <p class="text-lg font-semibold text-gray-900 mt-1">${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Items ({{ $order->orderItems->count() }})</h4>
                                    <div class="space-y-1">
                                        @foreach($order->orderItems->take(3) as $item)
                                        <p class="text-sm text-gray-600">
                                            {{ $item->quantity }}x {{ $item->product->name }}
                                        </p>
                                        @endforeach
                                        @if($order->orderItems->count() > 3)
                                        <p class="text-sm text-gray-500">+{{ $order->orderItems->count() - 3 }} more items</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Delivery Address</h4>
                                    <p class="text-sm text-gray-600">{{ Str::limit($order->delivery_address, 50) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Phone: {{ $order->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                @if($order->status === 'pending')
                                    <span class="flex items-center">
                                        <span class="mr-2">📞</span>
                                        Our staff will call you soon to confirm
                                    </span>
                                @elseif($order->status === 'accepted')
                                    <span class="flex items-center">
                                        <span class="mr-2">👨‍🍳</span>
                                        Chef is preparing your order
                                    </span>
                                @elseif($order->status === 'preparing')
                                    <span class="flex items-center">
                                        <span class="mr-2">🍣</span>
                                        Your sushi is being prepared
                                    </span>
                                @elseif($order->status === 'ready')
                                    <span class="flex items-center">
                                        <span class="mr-2">✅</span>
                                        Order is ready for pickup/delivery
                                    </span>
                                @elseif($order->status === 'out_for_delivery')
                                    <span class="flex items-center">
                                        <span class="mr-2">🚚</span>
                                        Out for delivery
                                    </span>
                                @elseif($order->status === 'delivered')
                                    <span class="flex items-center">
                                        <span class="mr-2">🎉</span>
                                        Delivered successfully
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="flex items-center">
                                        <span class="mr-2">❌</span>
                                        Order was cancelled
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to cancel this order?')"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
                                        Cancel Order
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('orders.show', $order) }}" class="text-yellow-600 hover:text-yellow-700 font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🍣</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h3>
                    <p class="text-gray-600 mb-6">Start your sushi journey by placing your first order!</p>
                    <a href="{{ route('home') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Browse Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
