@php
    use Illuminate\Support\Str;
@endphp

<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🎟️ Promo Codes Management</h1>
                    <p class="mt-2 text-gray-600">Create and manage discount codes and special offers</p>
                </div>
                <button onclick="document.getElementById('create-promo-modal').classList.remove('hidden')" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    + Create New Promo Code
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Promo Codes List -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">All Promo Codes</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Until</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($promoCodes as $promoCode)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono font-bold text-blue-600">{{ $promoCode->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $promoCode->name }}</div>
                                        @if($promoCode->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($promoCode->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($promoCode->discount_type === 'percentage')
                                            <span class="text-green-600 font-semibold">{{ $promoCode->discount_value }}%</span>
                                        @else
                                            <span class="text-green-600 font-semibold">{{ number_format($promoCode->discount_value, 2) }} MAD</span>
                                        @endif
                                        @if($promoCode->applicable_products)
                                            <div class="text-xs text-gray-500">Selected items only</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $promoCode->usages_count }}/{{ $promoCode->total_usage_limit ?? '∞' }}</div>
                                        <div class="text-xs text-gray-500">{{ $promoCode->usage_limit_per_user }}x per user</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($promoCode->expires_at)
                                            {{ $promoCode->expires_at->format('M d, Y') }}
                                        @else
                                            <span class="text-gray-400">No expiration</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($promoCode->is_active && $promoCode->isValid())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button onclick="editPromoCode({{ $promoCode->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                                        @if(!$promoCode->emailed_at)
                                            <form action="{{ route('admin.promo-codes.send-email', $promoCode) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Send Email</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">Emailed</span>
                                        @endif
                                        <form action="{{ route('admin.promo-codes.delete', $promoCode) }}" method="POST" class="inline" onsubmit="return confirm('Delete this promo code?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No promo codes created yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Promo Code Modal -->
    <div id="create-promo-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Create New Promo Code</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <form id="promo-code-form" method="POST" action="{{ route('admin.promo-codes.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Promo Code *</label>
                            <input type="text" name="code" id="code" required pattern="[A-Z0-9-_]+" 
                                value="{{ old('code') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('code') ? 'border-red-500' : '' }}"
                                placeholder="SUMMER20">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Uppercase letters, numbers, hyphens, and underscores only</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Display Name *</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Summer Special">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Describe this promo code..."></textarea>
                    </div>

                    <!-- Discount Settings -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold mb-4">Discount Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                                <select name="discount_type" id="discount_type" required onchange="updateDiscountLabel()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (MAD)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Discount Value * <span id="discount-label">(%)</span>
                                </label>
                                <input type="number" name="discount_value" id="discount_value" step="0.01" min="0.01" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="15">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order (MAD)</label>
                                <input type="number" name="minimum_order_amount" id="minimum_order_amount" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    <!-- Usage Limits -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold mb-4">Usage Limits</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Usage Limit Per User *</label>
                                <input type="number" name="usage_limit_per_user" id="usage_limit_per_user" required min="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    value="1">
                                <p class="text-xs text-gray-500 mt-1">How many times each user can use this code</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                                <input type="number" name="total_usage_limit" id="total_usage_limit" min="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Leave empty for unlimited">
                                <p class="text-xs text-gray-500 mt-1">Total times this code can be used (leave empty for unlimited)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Validity -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold mb-4">Validity</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valid For (Days)</label>
                            <input type="number" name="valid_for_days" id="valid_for_days" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Leave empty for no expiration">
                            <p class="text-xs text-gray-500 mt-1">Number of days from creation date (leave empty for no expiration)</p>
                        </div>
                    </div>

                    <!-- Applicable Products -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold mb-4">Applicable Products</h4>
                        <div class="mb-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="all-products" checked onchange="toggleProductSelection()" class="mr-2">
                                <span class="text-sm font-medium text-gray-700">Apply to all products</span>
                            </label>
                        </div>
                        <div id="product-selection" class="hidden max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            <p class="text-sm text-gray-600 mb-2">Select specific products:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($products as $product)
                                    <label class="flex items-center text-sm">
                                        <input type="checkbox" name="applicable_products[]" value="{{ $product->id }}" 
                                            class="product-checkbox mr-2" onchange="updateAllProductsCheckbox()">
                                        <span>{{ $product->name }} - {{ number_format($product->price, 2) }} MAD</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold mb-4">Options</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="send_email_to_users" value="1" class="mr-2">
                                <span class="text-sm text-gray-700">Send email to all customers when created</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                            Save Promo Code
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let editPromoCodeId = null;
        const promoCodes = @json($promoCodesJson);

        function closeModal() {
            document.getElementById('create-promo-modal').classList.add('hidden');
            document.getElementById('promo-code-form').reset();
            document.getElementById('form-method').value = 'POST';
            document.getElementById('promo-code-form').action = '{{ route("admin.promo-codes.store") }}';
            document.getElementById('modal-title').textContent = 'Create New Promo Code';
            editPromoCodeId = null;
            toggleProductSelection();
        }

        function editPromoCode(id) {
            const promoCode = promoCodes.find(pc => pc.id === id);
            if (!promoCode) return;

            editPromoCodeId = id;
            document.getElementById('modal-title').textContent = 'Edit Promo Code';
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('promo-code-form').action = '{{ route("admin.promo-codes.update", ":id") }}'.replace(':id', id);

            // Populate form
            document.getElementById('code').value = promoCode.code;
            document.getElementById('name').value = promoCode.name;
            document.getElementById('description').value = promoCode.description || '';
            document.getElementById('discount_type').value = promoCode.discount_type;
            document.getElementById('discount_value').value = promoCode.discount_value;
            document.getElementById('minimum_order_amount').value = promoCode.minimum_order_amount || '';
            document.getElementById('usage_limit_per_user').value = promoCode.usage_limit_per_user;
            document.getElementById('total_usage_limit').value = promoCode.total_usage_limit || '';
            document.getElementById('valid_for_days').value = promoCode.valid_for_days || '';
            
            // Checkboxes
            document.querySelector('input[name="is_active"]').checked = promoCode.is_active;
            document.querySelector('input[name="send_email_to_users"]').checked = promoCode.send_email_to_users;

            // Products
            if (promoCode.applicable_products && promoCode.applicable_products.length > 0) {
                document.getElementById('all-products').checked = false;
                document.getElementById('product-selection').classList.remove('hidden');
                document.querySelectorAll('.product-checkbox').forEach(cb => {
                    cb.checked = promoCode.applicable_products.includes(parseInt(cb.value));
                });
            } else {
                document.getElementById('all-products').checked = true;
                toggleProductSelection();
            }

            updateDiscountLabel();
            document.getElementById('create-promo-modal').classList.remove('hidden');
        }

        function updateDiscountLabel() {
            const type = document.getElementById('discount_type').value;
            document.getElementById('discount-label').textContent = type === 'percentage' ? '(%)' : '(MAD)';
        }

        function toggleProductSelection() {
            const allProducts = document.getElementById('all-products').checked;
            const productSelection = document.getElementById('product-selection');
            
            if (allProducts) {
                productSelection.classList.add('hidden');
                document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
            } else {
                productSelection.classList.remove('hidden');
            }
        }

        function updateAllProductsCheckbox() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            document.getElementById('all-products').checked = checkedCount === 0;
        }

        // Close modal when clicking outside
        document.getElementById('create-promo-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-admin-layout>
