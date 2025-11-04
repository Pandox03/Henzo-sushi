<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Chef Dashboard</h1>
                        <p class="mt-2 text-gray-600">Manage orders and track your cooking progress</p>
                    </div>
                    <div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="text-3xl">⏳</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Awaiting Orders</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $awaitingOrders->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="text-3xl">👨‍🍳</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">My Orders</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $chefOrders->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="text-3xl">✅</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Completed Today</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $chefOrders->where('status', 'delivered')->where('delivered_at', '>=', today())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Awaiting Orders -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Awaiting Orders</h2>
                    <p class="text-gray-600">New orders waiting for chef assignment</p>
                </div>
                
                @if($awaitingOrders->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($awaitingOrders as $order)
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="text-2xl">🍣</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-600">Customer: {{ $order->user->name }}</p>
                                            <p class="text-sm text-gray-600">Phone: {{ $order->phone }}</p>
                                            <p class="text-sm text-gray-600">Address: {{ $order->delivery_address }}</p>
                                            <p class="text-sm text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">Placed: {{ $order->created_at->format('M d, Y H:i') }}</p>
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
                                </div>
                                
                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('chef.orders.show', $order) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-center">
                                        View Details
                                    </a>
                                    <form action="{{ route('chef.orders.accept', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors w-full">
                                            Accept Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center">
                        <span class="text-4xl">🍣</span>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No awaiting orders</h3>
                        <p class="text-gray-600">All caught up! New orders will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- My Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">My Orders</h2>
                        <p class="text-gray-600">Orders you're currently handling</p>
                    </div>
                    <a href="{{ route('chef.orders.history') }}" class="text-yellow-600 hover:text-yellow-700 font-medium">
                        View All History →
                    </a>
                </div>
                
                @if($chefOrders->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($chefOrders->take(5) as $order)
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
                                            @else
                                                <span class="text-2xl">🍣</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-600">Customer: {{ $order->user->name }}</p>
                                            <p class="text-sm text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">Accepted: {{ $order->accepted_at ? $order->accepted_at->format('M d, Y H:i') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ml-6 flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'accepted') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'preparing') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'ready') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    
                                    @if($order->status === 'accepted')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('chef.orders.status', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="preparing">
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-1 px-3 rounded">
                                                    Start Preparing
                                                </button>
                                            </form>
                                            <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Print Bill
                                            </a>
                                        </div>
                                    @elseif($order->status === 'preparing')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('chef.orders.status', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="ready">
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded">
                                                    Mark Ready
                                                </button>
                                            </form>
                                            <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Print Bill
                                            </a>
                                        </div>
                                    @elseif($order->status === 'ready')
                                        <div class="flex space-x-2">
                                            <button onclick="openDeliveryModal({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded">
                                                Assign Delivery
                                            </button>
                                            <a href="{{ route('chef.orders.print-bill', $order) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Print Bill
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center">
                        <span class="text-4xl">👨‍🍳</span>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                        <p class="text-gray-600">Accept orders from the awaiting list above.</p>
                    </div>
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
