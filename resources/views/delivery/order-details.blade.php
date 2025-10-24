<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Details</h1>
                        <p class="text-lg text-gray-600">Order #{{ $order->order_number }}</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('delivery.dashboard') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            ← Back to Dashboard
                        </a>
                        
                        @if($order->status === 'out_for_delivery')
                        <a href="{{ route('delivery.orders.navigation', $order) }}" 
                           class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                            🗺️ Start Navigation
                        </a>
                        
                        <button onclick="markDelivered({{ $order->id }})" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Mark as Delivered
                        </button>
                        
                        <button onclick="markCancelled({{ $order->id }})" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Cancel Order
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Information</h2>
                    
                    <!-- Customer Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Customer Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">👤</span>
                                <span class="font-medium">{{ $order->user->name }} {{ $order->user->last_name }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📞</span>
                                <span>{{ $order->phone }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📧</span>
                                <span>{{ $order->user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Delivery Address</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <span class="text-gray-500 mt-1">📍</span>
                                <div>
                                    <p class="font-medium">{{ $order->delivery_address }}</p>
                                    @if($order->delivery_latitude && $order->delivery_longitude)
                                    <button onclick="openMap({{ $order->delivery_latitude }}, {{ $order->delivery_longitude }})" 
                                            class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                                        🗺️ Open in Maps
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Status</h3>
                        <div class="flex items-center space-x-3">
                            @if($order->status === 'out_for_delivery')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    🚚 Out for Delivery
                                </span>
                            @elseif($order->status === 'delivered')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✅ Delivered
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ❌ Cancelled
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Timeline</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Order Placed</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($order->accepted_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Order Accepted</p>
                                    <p class="text-sm text-gray-500">{{ $order->accepted_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($order->out_for_delivery_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">🚚</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Out for Delivery</p>
                                    <p class="text-sm text-gray-500">{{ $order->out_for_delivery_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($order->delivered_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Delivered</p>
                                    <p class="text-sm text-gray-500">{{ $order->delivered_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Items</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🍣</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-600">Price: {{ $item->unit_price }} MAD each</p>
                                @if($item->special_instructions)
                                <p class="text-sm text-blue-600">Note: {{ $item->special_instructions }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ $item->total_price }} MAD</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center text-lg font-semibold">
                            <span>Total Amount:</span>
                            <span class="text-blue-600">{{ $order->total_amount }} MAD</span>
                        </div>
                        @if($order->delivery_fee > 0)
                        <div class="flex justify-between items-center text-sm text-gray-600 mt-1">
                            <span>Delivery Fee:</span>
                            <span>{{ $order->delivery_fee }} MAD</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function markDelivered(orderId) {
            if (confirm('Are you sure you want to mark this order as delivered?')) {
                fetch(`/delivery/orders/${orderId}/delivered`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function markCancelled(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                fetch(`/delivery/orders/${orderId}/cancelled`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function openMap(latitude, longitude) {
            const url = `https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(url, '_blank');
        }
    </script>
</x-app-layout>
