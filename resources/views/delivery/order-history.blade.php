<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">📋 Delivery History</h1>
                        <p class="text-lg text-gray-600">Your complete delivery history and statistics</p>
                    </div>
                    <a href="{{ route('delivery.dashboard') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">📦</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $orders->total() }}</h3>
                            <p class="text-gray-600">Total Orders</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'delivered')->count() }}</h3>
                            <p class="text-gray-600">Delivered</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">❌</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'cancelled')->count() }}</h3>
                            <p class="text-gray-600">Cancelled</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'delivered')->sum('total_amount') }} MAD</h3>
                            <p class="text-gray-600">Total Value</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">All Orders</h2>

                @if($orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
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
                                        <div class="text-sm text-gray-500">
                                            @if($order->out_for_delivery_at)
                                                Assigned: {{ $order->out_for_delivery_at->format('M d, H:i') }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'out_for_delivery') bg-orange-100 text-orange-800
                                            @endif">
                                            @if($order->status === 'delivered') ✅ Delivered
                                            @elseif($order->status === 'cancelled') ❌ Cancelled
                                            @elseif($order->status === 'out_for_delivery') 🚚 Out for Delivery
                                            @endif
                                        </span>
                                        
                                        @if($order->delivered_at)
                                        <div class="text-xs text-gray-500">
                                            Delivered: {{ $order->delivered_at->format('M d, H:i') }}
                                        </div>
                                        @endif
                                        
                                        <a href="{{ route('delivery.orders.show', $order) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors text-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Orders Found</h3>
                        <p class="text-gray-600">You haven't delivered any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


