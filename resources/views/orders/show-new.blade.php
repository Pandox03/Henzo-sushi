<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Tracking</h1>
                <p class="text-lg text-gray-600">Order #{{ $order->order_number }}</p>
            </div>

            <!-- Real-time Status Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 order-status">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center bg-blue-500">
                        <span class="text-white text-2xl">⏳</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Order Pending</h3>
                        <p class="text-gray-600">Your order is waiting to be accepted by a chef</p>
                    </div>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Progress</h2>
                <div class="order-timeline space-y-6">
                    <!-- Timeline steps will be populated by JavaScript -->
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🍣</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-600">Price: {{ $item->unit_price }} MAD each</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ $item->total_price }} MAD</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t pt-4 mt-6 space-y-1">
                        @if(($order->discount_percent ?? 0) > 0)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-800">{{ number_format($order->total_amount + $order->discount_amount, 2) }} MAD</span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-green-700">
                                <span>Discount ({{ $order->discount_percent }}%)</span>
                                <span>-{{ number_format($order->discount_amount, 2) }} MAD</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($order->total_amount, 2) }} MAD</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Delivery Information</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600">📍</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Delivery Address</h4>
                                <p class="text-gray-600">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-green-600">📞</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Contact Number</h4>
                                <p class="text-gray-600">{{ $order->phone }}</p>
                            </div>
                        </div>
                        
                        @if($order->notes)
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <span class="text-yellow-600">📝</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Special Instructions</h4>
                                <p class="text-gray-600">{{ $order->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center space-x-4">
                @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Cancel Order
                    </button>
                </form>
                @endif

                @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                <div class="mt-4">
                    <a href="{{ route('orders.tracking', $order) }}" 
                       class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        🗺️ Live Delivery Tracking
                    </a>
                </div>
                @endif
                
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Back to Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Custom CSS for animations -->
    <style>
        .timeline-step {
            display: flex;
            align-items: flex-start;
            position: relative;
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.5s ease;
        }
        
        .timeline-step.completed {
            opacity: 1;
            transform: translateX(0);
        }
        
        .timeline-step.active {
            opacity: 1;
            transform: translateX(0);
            animation: pulse 2s infinite;
        }
        
        .timeline-marker {
            position: relative;
            margin-right: 1rem;
        }
        
        .marker-circle {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .timeline-step.completed .marker-circle {
            background-color: #10b981 !important;
            transform: scale(1.1);
        }
        
        .timeline-step.active .marker-circle {
            background-color: #3b82f6 !important;
            animation: bounce 1s infinite;
        }
        
        .marker-line {
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 3rem;
            background-color: #e5e7eb;
        }
        
        .timeline-step.completed .marker-line {
            background-color: #10b981;
        }
        
        .timeline-content {
            flex: 1;
            padding-top: 0.25rem;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) scale(1);
            }
            40% {
                transform: translateY(-10px) scale(1.1);
            }
            60% {
                transform: translateY(-5px) scale(1.05);
            }
        }
        
        .order-status {
            transition: all 0.3s ease;
        }
    </style>

    <!-- Pass order data to JavaScript -->
    <script>
        window.orderData = @json($order);
    </script>

    <!-- Real-time Order Tracking -->
    <script src="{{ asset('js/customer-realtime.js') }}"></script>
</x-app-layout>
