<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Message -->
            @if($order->status === 'cancelled')
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-red-800">Order Cancelled</h3>
                        <p class="text-red-700">This order has been cancelled and will not be processed.</p>
                    </div>
                </div>
            </div>
            @elseif($order->status === 'delivered')
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-green-800">Order Delivered Successfully!</h3>
                        <p class="text-green-700">Thank you for choosing Henzo Sushi! We hope you enjoyed your meal.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-green-800">Order Placed Successfully!</h3>
                        <p class="text-green-700">Your order has been received and is being processed.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Order Header -->
                <div class="bg-yellow-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Order #{{ $order->order_number }}</h1>
                            <p class="text-yellow-100">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="px-6 py-4 border-b border-gray-200 {{ $order->status === 'cancelled' ? 'bg-red-50' : 'bg-yellow-50' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($order->status === 'cancelled')
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @elseif($order->status === 'pending')
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($order->status === 'accepted')
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($order->status === 'preparing')
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            @elseif($order->status === 'ready')
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($order->status === 'out_for_delivery')
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            @elseif($order->status === 'delivered')
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-4">
                            @if($order->status === 'cancelled')
                                <h3 class="text-lg font-semibold text-gray-900">Order Cancelled</h3>
                                <p class="text-gray-600">This order has been cancelled and will not be processed.</p>
                            @elseif($order->status === 'pending')
                                <h3 class="text-lg font-semibold text-gray-900">Our staff will call you soon!</h3>
                                <p class="text-gray-600">We'll contact you at <strong>{{ $order->phone }}</strong> to confirm your order details.</p>
                            @elseif($order->status === 'accepted')
                                <h3 class="text-lg font-semibold text-gray-900">Order Accepted!</h3>
                                <p class="text-gray-600">Chef has accepted your order and will start preparing soon. We'll contact you at <strong>{{ $order->phone }}</strong> if needed.</p>
                            @elseif($order->status === 'preparing')
                                <h3 class="text-lg font-semibold text-gray-900">Order in Preparation</h3>
                                <p class="text-gray-600">Your sushi is being prepared with care. We'll contact you at <strong>{{ $order->phone }}</strong> when ready.</p>
                            @elseif($order->status === 'ready')
                                <h3 class="text-lg font-semibold text-gray-900">Order Ready!</h3>
                                <p class="text-gray-600">Your order is ready for pickup/delivery. We'll contact you at <strong>{{ $order->phone }}</strong> for delivery details.</p>
                            @elseif($order->status === 'out_for_delivery')
                                <h3 class="text-lg font-semibold text-gray-900">Out for Delivery</h3>
                                <p class="text-gray-600">Your order is on its way! 
                                    @if($order->deliveryGuy)
                                        <strong>{{ $order->deliveryGuy->name }}</strong> is delivering your order.
                                    @endif
                                    We'll contact you at <strong>{{ $order->phone }}</strong> for delivery updates.
                                </p>
                            @elseif($order->status === 'delivered')
                                <h3 class="text-lg font-semibold text-gray-900">Order Delivered!</h3>
                                <p class="text-gray-600">Thank you for choosing Henzo Sushi! We hope you enjoyed your meal.</p>
                            @else
                                <h3 class="text-lg font-semibold text-gray-900">Our staff will call you soon!</h3>
                                <p class="text-gray-600">We'll contact you at <strong>{{ $order->phone }}</strong> to confirm your order details.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-6 py-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-500">Unit Price: ${{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="px-6 py-6 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Delivery Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Delivery Information</h3>
                            <div class="space-y-2">
                                <p class="text-gray-600">
                                    <span class="font-medium">Address:</span><br>
                                    {{ $order->delivery_address }}
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium">Phone:</span> {{ $order->phone }}
                                </p>
                                @if($order->notes)
                                <p class="text-gray-600">
                                    <span class="font-medium">Special Instructions:</span><br>
                                    {{ $order->notes }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Order Total -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Summary</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">${{ number_format($order->total_amount - $order->delivery_fee, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Delivery Fee:</span>
                                    <span class="font-medium">${{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold border-t border-gray-300 pt-2">
                                    <span>Total:</span>
                                    <span class="text-yellow-600">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 text-center inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Order
                    </button>
                </form>
                @endif
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 text-center inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    View All Orders
                </a>
                <a href="{{ route('home') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 text-center inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
