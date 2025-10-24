<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                        <p class="mt-2 text-gray-600">Order #{{ $order->order_number }}</p>
                    </div>
                    <a href="{{ route('chef.dashboard') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($order->status === 'pending')
                                <span class="text-2xl mr-3">⏳</span>
                            @elseif($order->status === 'accepted')
                                <span class="text-2xl mr-3">👨‍🍳</span>
                            @elseif($order->status === 'preparing')
                                <span class="text-2xl mr-3">🍣</span>
                            @elseif($order->status === 'ready')
                                <span class="text-2xl mr-3">✅</span>
                            @elseif($order->status === 'out_for_delivery')
                                <span class="text-2xl mr-3">🚚</span>
                            @elseif($order->status === 'delivered')
                                <span class="text-2xl mr-3">🎉</span>
                            @elseif($order->status === 'cancelled')
                                <span class="text-2xl mr-3">❌</span>
                            @endif
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Order Status</h2>
                                <p class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                            @elseif($order->status === 'preparing') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                            @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $order->phone ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $order->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Delivery Address</label>
                            <p class="text-gray-900">{{ $order->delivery_address }}</p>
                        </div>
                        @if($order->delivery_latitude && $order->delivery_longitude)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Coordinates</label>
                            <p class="text-gray-900">{{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Order Number</label>
                            <p class="text-gray-900 font-mono">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Order Date</label>
                            <p class="text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($order->chef)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Assigned Chef</label>
                            <p class="text-gray-900">{{ $order->chef->name }}</p>
                        </div>
                        @endif
                        @if($order->deliveryGuy)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Delivery Person</label>
                            <p class="text-gray-900">{{ $order->deliveryGuy->name }} ({{ $order->deliveryGuy->phone }})</p>
                        </div>
                        @endif
                        @if($order->accepted_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Accepted At</label>
                            <p class="text-gray-900">{{ $order->accepted_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                        @if($order->preparing_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Started Preparing</label>
                            <p class="text-gray-900">{{ $order->preparing_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                        @if($order->ready_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Ready At</label>
                            <p class="text-gray-900">{{ $order->ready_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200 last:border-b-0">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🍣</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-500">Unit Price: ${{ number_format($item->unit_price, 2) }}</p>
                                @if($item->special_instructions)
                                <p class="text-sm text-blue-600 mt-1">Special: {{ $item->special_instructions }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Special Instructions -->
            @if($order->notes)
            <div class="bg-white rounded-lg shadow mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Special Instructions</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-900">{{ $order->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">${{ number_format($order->total_amount - $order->delivery_fee, 2) }}</span>
                        </div>
                        @if($order->delivery_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee:</span>
                            <span class="text-gray-900">${{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                            <span>Total:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                @if($order->status === 'pending')
                <form action="{{ route('chef.orders.accept', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Accept Order
                    </button>
                </form>
                @elseif($order->status === 'accepted')
                <div class="flex space-x-4">
                    <form action="{{ route('chef.orders.status', $order) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="preparing">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Start Preparing
                        </button>
                    </form>
                    <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Print Bill
                    </a>
                </div>
                @elseif($order->status === 'preparing')
                <div class="flex space-x-4">
                    <form action="{{ route('chef.orders.status', $order) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Mark Ready
                        </button>
                    </form>
                    <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Print Bill
                    </a>
                </div>
                @elseif($order->status === 'ready')
                <div class="flex space-x-4">
                    <button onclick="openDeliveryModal({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Assign Delivery
                    </button>
                    <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Print Bill
                    </a>
                </div>
                @elseif(in_array($order->status, ['out_for_delivery', 'delivered']))
                <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Print Bill
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Delivery Assignment Modal -->
    <div id="deliveryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Assign Delivery Person</h3>
                    <button onclick="closeDeliveryModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="deliveryForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="delivery_guy_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Delivery Person
                        </label>
                        <select id="delivery_guy_id" name="delivery_guy_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                            <option value="">Loading delivery persons...</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeliveryModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            Assign Delivery
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;

        function openDeliveryModal(orderId) {
            currentOrderId = orderId;
            document.getElementById('deliveryModal').classList.remove('hidden');
            loadDeliveryGuys();
        }

        function closeDeliveryModal() {
            document.getElementById('deliveryModal').classList.add('hidden');
            currentOrderId = null;
        }

        function loadDeliveryGuys() {
            fetch('{{ route("chef.delivery-guys") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const select = document.getElementById('delivery_guy_id');
                    select.innerHTML = '<option value="">Select a delivery person</option>';
                    
                    if (data && data.length > 0) {
                        data.forEach(deliveryGuy => {
                            const option = document.createElement('option');
                            option.value = deliveryGuy.id;
                            option.textContent = `${deliveryGuy.name} (${deliveryGuy.phone})`;
                            select.appendChild(option);
                        });
                    } else {
                        select.innerHTML = '<option value="">No delivery persons available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading delivery guys:', error);
                    document.getElementById('delivery_guy_id').innerHTML = '<option value="">Error loading delivery persons: ' + error.message + '</option>';
                });
        }

        document.getElementById('deliveryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentOrderId) {
                alert('No order selected');
                return;
            }

            const formData = new FormData();
            formData.append('delivery_guy_id', document.getElementById('delivery_guy_id').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const deliveryGuyId = formData.get('delivery_guy_id');
            
            if (!deliveryGuyId) {
                alert('Please select a delivery person');
                return;
            }

            console.log('Submitting delivery assignment:', {
                orderId: currentOrderId,
                deliveryGuyId: deliveryGuyId,
                url: `/chef/orders/${currentOrderId}/assign-delivery`
            });

            fetch(`/chef/orders/${currentOrderId}/assign-delivery`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    closeDeliveryModal();
                    location.reload(); // Reload to show updated status
                } else {
                    alert(data.message || 'Error assigning delivery');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error assigning delivery: ' + error.message);
            });
        });
    </script>
    
    <!-- Real-time Notifications -->
    <script src="{{ asset('js/chef-realtime.js') }}"></script>
</x-app-layout>
