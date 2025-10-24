<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Order History</h1>
                        <p class="mt-2 text-gray-600">All orders you've handled</p>
                    </div>
                    <a href="{{ route('chef.dashboard') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white rounded-lg shadow">
                @if($orders->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($order->status === 'accepted')
                                                <span class="text-2xl">👨‍🍳</span>
                                            @elseif($order->status === 'preparing')
                                                <span class="text-2xl">🍣</span>
                                            @elseif($order->status === 'ready')
                                                <span class="text-2xl">✅</span>
                                            @elseif($order->status === 'out_for_delivery')
                                                <span class="text-2xl">🚚</span>
                                            @elseif($order->status === 'delivered')
                                                <span class="text-2xl">🎉</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="text-2xl">❌</span>
                                            @else
                                                <span class="text-2xl">🍣</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-600">Customer: {{ $order->user->name }}</p>
                                            <p class="text-sm text-gray-600">Phone: {{ $order->phone }}</p>
                                            <p class="text-sm text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">Accepted: {{ $order->accepted_at ? $order->accepted_at->format('M d, Y H:i') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items -->
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Order Items:</h4>
                                        <div class="space-y-1">
                                            @foreach($order->orderItems as $item)
                                            <div class="flex justify-between text-sm">
                                                <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                                                <span>${{ number_format($item->total_price, 2) }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($order->notes)
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-900">Special Instructions:</h4>
                                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                                    </div>
                                    @endif
                                    
                                    <!-- Timeline -->
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Timeline:</h4>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <p>Order placed: {{ $order->created_at->format('M d, Y H:i') }}</p>
                                            @if($order->accepted_at)
                                                <p>Accepted: {{ $order->accepted_at->format('M d, Y H:i') }}</p>
                                            @endif
                                            @if($order->preparing_at)
                                                <p>Started preparing: {{ $order->preparing_at->format('M d, Y H:i') }}</p>
                                            @endif
                                            @if($order->ready_at)
                                                <p>Ready: {{ $order->ready_at->format('M d, Y H:i') }}</p>
                                            @endif
                                            @if($order->out_for_delivery_at)
                                                <p>Out for delivery: {{ $order->out_for_delivery_at->format('M d, Y H:i') }}</p>
                                            @endif
                                            @if($order->delivered_at)
                                                <p>Delivered: {{ $order->delivered_at->format('M d, Y H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ml-6 text-right">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'accepted') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'preparing') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'ready') bg-green-100 text-green-800
                                        @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    
                                    @if($order->status === 'accepted')
                                        <div class="mt-2 space-y-1">
                                            <form action="{{ route('chef.orders.status', $order) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="preparing">
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-1 px-3 rounded w-full">
                                                    Start Preparing
                                                </button>
                                            </form>
                                            <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded w-full block text-center">
                                                Print Bill
                                            </a>
                                        </div>
                                    @elseif($order->status === 'preparing')
                                        <div class="mt-2 space-y-1">
                                            <form action="{{ route('chef.orders.status', $order) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ready">
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded w-full">
                                                    Mark Ready
                                                </button>
                                            </form>
                                            <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded w-full block text-center">
                                                Print Bill
                                            </a>
                                        </div>
                                    @elseif(in_array($order->status, ['ready', 'out_for_delivery', 'delivered']))
                                        <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded w-full block text-center">
                                            Print Bill
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="p-6 text-center">
                        <span class="text-4xl">👨‍🍳</span>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                        <p class="text-gray-600">Your order history will appear here once you start accepting orders.</p>
                        <a href="{{ route('chef.dashboard') }}" class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Go to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
