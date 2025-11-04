<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">🚚 Delivery Dashboard</h1>
                        <p class="text-lg text-gray-600">Welcome back, {{ Auth::user()->name }}! Manage your delivery orders</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Last update</div>
                        <div class="text-lg font-semibold text-gray-900">{{ now()->format('H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">📦</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $todayStats['total_orders'] }}</h3>
                            <p class="text-gray-600">Total Orders Today</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $todayStats['delivered_orders'] }}</h3>
                            <p class="text-gray-600">Delivered Today</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🚚</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $todayStats['pending_orders'] }}</h3>
                            <p class="text-gray-600">In Progress</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Orders -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Your Assigned Orders</h2>
                    <a href="{{ route('delivery.history') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        View History
                    </a>
                </div>

                @if($assignedOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignedOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-xl">📦</span>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                            <p class="text-gray-600">{{ $order->user->name }} • {{ $order->user->phone }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->delivery_address }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900">{{ $order->total_amount }} MAD</div>
                                        <div class="text-sm text-gray-500">{{ $order->orderItems->count() }} items</div>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($order->status === 'out_for_delivery') bg-orange-100 text-orange-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @endif">
                                            @if($order->status === 'out_for_delivery') 🚚 Out for Delivery
                                            @elseif($order->status === 'delivered') ✅ Delivered
                                            @endif
                                        </span>
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('delivery.orders.show', $order) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                View Details
                                            </a>
                                            
                                            @if($order->status === 'out_for_delivery')
                                            <a href="{{ route('delivery.orders.navigation', $order) }}" 
                                               class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                🗺️ Navigate
                                            </a>
                                            
                                            <button onclick="markDelivered({{ $order->id }})" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                Mark Delivered
                                            </button>
                                            
                                            <button onclick="markCancelled({{ $order->id }})" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                Cancel
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📦</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Orders Assigned</h3>
                        <p class="text-gray-600">You don't have any orders assigned to you at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for order actions -->
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
    </script>
</x-app-layout>
