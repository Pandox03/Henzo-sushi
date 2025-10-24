<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($cartItems) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Cart Items -->
                            <div class="lg:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Your Items</h3>
                                <div class="space-y-4">
                                    @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item['product']->image ?: 'https://images.unsplash.com/photo-' . (['1544551763-46a013bb2dcc', '1551218808-94e220e084d2', '1565299624946-b28f40c0fe4b', '1571019613454-1cb2f99b2d8b', '1578662996442-48f60103fc96', '1586190848861-99aa4bd1711f'][$item['product']->id % 6]) . '?w=100&h=100&fit=crop&crop=center' }}" 
                                                 alt="{{ $item['product']->name }}" 
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $item['product']->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item['product']->description }}</p>
                                            <p class="text-sm text-gray-500">Preparation time: {{ $item['product']->preparation_time }} minutes</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" 
                                                    class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                                                -
                                            </button>
                                            <span class="w-8 text-center">{{ $item['quantity'] }}</span>
                                            <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})" 
                                                    class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                                                +
                                            </button>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-yellow-600">${{ number_format($item['total'], 2) }}</p>
                                            <button onclick="removeItem({{ $item['product']->id }})" 
                                                    class="text-red-500 hover:text-red-700 text-sm">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6">
                                    <button onclick="clearCart()" 
                                            class="text-red-500 hover:text-red-700 text-sm">
                                        Clear Cart
                                    </button>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                                    
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span>${{ number_format($total, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Delivery Fee:</span>
                                            <span>$2.99</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Tax:</span>
                                            <span>${{ number_format($total * 0.08, 2) }}</span>
                                        </div>
                                        
                                        @php
                                            $discount = 0;
                                            if (session('promo_code') && session('discount_percent')) {
                                                $discount = $total * (session('discount_percent') / 100);
                                            }
                                            $subtotal = $total;
                                            $totalWithTax = $total + ($total * 0.08);
                                            $finalTotal = $totalWithTax + 2.99 - $discount;
                                        @endphp
                                        
                                        @if(session('promo_code') && session('discount_percent'))
                                            <div class="flex justify-between text-green-600">
                                                <span>Discount ({{ session('promo_code') }} - {{ session('discount_percent') }}%):</span>
                                                <span>-${{ number_format($discount, 2) }}</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Promo Code Section -->
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex items-center mb-2">
                                                <input type="text" id="promoCode" placeholder="Enter promo code" 
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                                    value="{{ session('promo_code') ?? '' }}">
                                                <button type="button" onclick="applyPromoCode()" 
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-r-md transition-colors">
                                                    Apply
                                                </button>
                                            </div>
                                            <div id="promoMessage" class="text-sm mt-1">
                                                @if(session('promo_code'))
                                                    <div class="flex justify-between items-center text-green-600">
                                                        <span>Promo code applied: {{ session('promo_code') }} ({{ session('discount_percent') }}% off)</span>
                                                        <button type="button" onclick="removePromoCode()" class="text-red-500 hover:text-red-700 text-xs">
                                                            Remove
                                                        </button>
                                                    </div>
                                                @elseif(session('promo_error'))
                                                    <p class="text-red-500">{{ session('promo_error') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <hr class="my-2">
                                        <div class="flex justify-between font-semibold text-lg">
                                            <span>Total:</span>
                                            <span>${{ number_format($finalTotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('orders.checkout') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors block text-center">
                                        🍣 Confirm My Order
                                    </a>
                                    
                                    <p class="text-xs text-gray-500 mt-2 text-center">
                                        Estimated delivery: 30-45 minutes
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-6xl mb-4">🛒</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                        <p class="text-gray-600 mb-6">Add some delicious sushi to get started!</p>
                        <a href="{{ route('products.index') }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Browse Menu
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function applyPromoCode() {
            const promoCode = document.getElementById('promoCode').value.trim();
            const promoMessage = document.getElementById('promoMessage');
            
            if (!promoCode) {
                promoMessage.innerHTML = '<p class="text-yellow-600">Please enter a promo code</p>';
                return;
            }

            // Show loading state
            promoMessage.innerHTML = '<p class="text-gray-600">Applying promo code...</p>';

            fetch('{{ route("cart.apply-promo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    promo_code: promoCode
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message briefly before reload
                    promoMessage.innerHTML = `<p class="text-green-600">${data.message}</p>`;
                    setTimeout(() => location.reload(), 800);
                } else {
                    throw new Error(data.message || 'Failed to apply promo code');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = error.message || 'An error occurred while applying the promo code';
                promoMessage.innerHTML = `<p class="text-red-500">${errorMessage}</p>`;
            });
        }

        function removePromoCode() {
            fetch('{{ route("cart.remove-promo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function updateQuantity(productId, quantity) {
            if (quantity < 0) return;
            
            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function removeItem(productId) {
            if (confirm('Are you sure you want to remove this item?')) {
                fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function clearCart() {
            if (confirm('Are you sure you want to clear your cart?')) {
                fetch('{{ route("cart.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</x-app-layout>
