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
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Order Pending</h3>
                        <p class="text-gray-600">Your order is waiting to be accepted by a chef</p>
                    </div>
                </div>
            </div>

            <!-- Progress Timeline with Glovo-style Animation -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Progress</h2>
                <div class="order-timeline space-y-0">
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
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
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
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Delivery Address</h4>
                                <p class="text-gray-600">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Contact Number</h4>
                                <p class="text-gray-600">{{ $order->phone }}</p>
                            </div>
                        </div>
                        
                        @if($order->notes)
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
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
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Order
                    </button>
                </form>
                @endif

                @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                <div class="mt-4">
                    <a href="{{ route('orders.tracking', $order) }}" 
                       class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Live Delivery Tracking
                    </a>
                </div>
                @endif
                
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Glovo-style animations -->
    <style>
        .timeline-step {
            display: flex;
            align-items: center;
            position: relative;
            padding: 1.5rem 0;
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
        }
        
        .timeline-marker {
            position: relative;
            margin-right: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        
        .marker-circle {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .timeline-step.completed .marker-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transform: scale(1);
            animation: successPulse 0.6s ease-out;
        }
        
        .timeline-step.active .marker-circle {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            animation: activePulse 2s infinite;
        }
        
        .timeline-step:not(.completed):not(.active) .marker-circle {
            background: #e5e7eb;
        }
        
        .marker-icon {
            width: 1.75rem;
            height: 1.75rem;
        }
        
        .timeline-step.completed .marker-icon {
            color: white;
            animation: checkBounce 0.6s ease-out;
        }
        
        .timeline-step.active .marker-icon {
            color: white;
        }
        
        .timeline-step:not(.completed):not(.active) .marker-icon {
            color: #9ca3af;
        }
        
        .marker-line {
            position: absolute;
            top: 3.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom, #e5e7eb 0%, #e5e7eb 100%);
        }
        
        .timeline-step.completed .marker-line {
            background: linear-gradient(to bottom, #10b981 0%, #10b981 100%);
            animation: lineGrow 0.6s ease-out;
        }
        
        .timeline-step:last-child .marker-line {
            display: none;
        }
        
        .timeline-content {
            flex: 1;
            padding: 0.75rem 1.5rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .timeline-step.active .timeline-content {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
        }
        
        .timeline-step.completed .timeline-content {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        
        @keyframes activePulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }
            50% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }
        }
        
        @keyframes successPulse {
            0% {
                transform: scale(0.8);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes checkBounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-5px);
            }
            60% {
                transform: translateY(-3px);
            }
        }
        
        @keyframes lineGrow {
            from {
                transform: translateX(-50%) scaleY(0);
                transform-origin: top;
            }
            to {
                transform: translateX(-50%) scaleY(1);
                transform-origin: top;
            }
        }
        
        .order-status {
            transition: all 0.3s ease;
        }

        /* Spinner animation for active status */
        .spinner {
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Pass order data to JavaScript -->
    <script>
        window.orderData = @json($order);
    </script>

    <!-- Real-time Order Tracking -->
    <script src="{{ asset('js/customer-realtime.js') }}"></script>
</x-app-layout>
