@extends('layouts.dashboard')

@section('main-content-class', 'has-right-sidebar')

@section('sidebar-nav')
    <a href="{{ route('customer.dashboard') }}" class="nav-item active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Home</span>
    </a>

    <a href="{{ route('customer.dashboard') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span>Food Order</span>
    </a>

    <a href="{{ route('customer.liked') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span>Liked</span>
    </a>

    <a href="{{ route('customer.feedback') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <span>Feedback</span>
    </a>

    <a href="{{ route('customer.messages') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span>Message</span>
    </a>

    <a href="{{ route('orders.index') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Order History</span>
    </a>

    <a href="{{ route('profile.edit') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        <span>Payment Details</span>
    </a>

    <a href="{{ route('customer.customization') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Customization</span>
    </a>
@endsection

@section('content')
<style>
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, rgba(255, 87, 34, 0.1) 0%, rgba(255, 87, 34, 0.05) 100%);
        border-radius: 20px;
        padding: 3rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-banner h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-japanese {
        color: var(--primary-orange);
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .hero-banner p {
        font-size: 1.125rem;
        color: var(--text-secondary);
        max-width: 500px;
    }

    .hero-image {
        position: absolute;
        right: -50px;
        top: 50%;
        transform: translateY(-50%);
        width: 400px;
        height: 400px;
        opacity: 0.3;
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .filter-button {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 0.625rem 1rem;
        border-radius: 10px;
        color: var(--text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-button:hover {
        border-color: var(--primary-orange);
        color: var(--primary-orange);
    }

    /* Category Pills */
    .category-pills {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .category-pill {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-pill:hover {
        background: var(--border-color);
        color: var(--text-primary);
    }

    .category-pill.active {
        background: var(--primary-orange);
        color: white;
        border-color: var(--primary-orange);
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-orange);
        box-shadow: 0 8px 24px rgba(255, 87, 34, 0.2);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: var(--bg-dark);
    }

    .product-info {
        padding: 1.25rem;
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .product-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .product-japanese {
        font-size: 0.875rem;
        color: var(--primary-orange);
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-orange);
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .add-to-cart-btn {
        background: var(--primary-orange);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .add-to-cart-btn:hover {
        background: var(--primary-orange-dark);
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .hero-banner {
            padding: 2rem;
        }

        .hero-banner h1 {
            font-size: 2rem;
        }

        .hero-japanese {
            font-size: 1.5rem;
        }

        .hero-image {
            display: none;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h1>
            <span class="hero-japanese">サムライ寿司</span>
            Sushi
        </h1>
        <p>Experience authentic Japanese cuisine with fresh ingredients and traditional preparation methods.</p>
    </div>
    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=800&h=800&fit=crop" alt="Sushi" style="width: 100%; height: 100%; object-fit: contain; transform: rotate(-15deg);">
    </div>
</div>

<!-- Section Header -->
<div class="section-header">
    <h2 class="section-title">Find The Sushi You Want</h2>
    <button class="filter-button" onclick="toggleFilters()">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        Filter
    </button>
</div>

<!-- Category Pills -->
<div class="category-pills">
    <button class="category-pill active" data-category="all">
        <span>All Sushi</span>
    </button>
    @foreach($categories as $category)
    <button class="category-pill" data-category="{{ $category->id }}">
        <span>{{ $category->name }}</span>
    </button>
    @endforeach
</div>

<!-- Products Grid -->
<div class="products-grid">
    @foreach($products as $product)
    <div class="product-card" data-category="{{ $product->category_id }}">
        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=600&h=400&fit=crop' }}" 
             alt="{{ $product->name }}" 
             class="product-image">
        <div class="product-info">
            <div class="product-header">
                <div>
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-japanese">{{ $product->japanese_name ?? '寿司' }}</div>
                </div>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
            </div>
            <div class="product-footer">
                <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('right-sidebar')
<style>
    .right-sidebar {
        width: 350px;
        background: var(--bg-dark);
        border-left: 1px solid var(--border-color);
        padding: 2rem;
        position: fixed;
        right: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 80;
    }

    .profile-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-card .user-avatar {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }

    .profile-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .profile-handle {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .cart-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .cart-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .clear-cart-btn {
        color: var(--primary-orange);
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .cart-items {
        margin-bottom: 1.5rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        background: var(--bg-dark);
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .cart-item-price {
        font-size: 0.875rem;
        color: var(--primary-orange);
        font-weight: 600;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quantity-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: var(--border-color);
    }

    .quantity {
        font-size: 0.9375rem;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .cart-total {
        padding: 1rem;
        background: rgba(255, 87, 34, 0.1);
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 1rem;
        font-weight: 600;
    }

    .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-orange);
    }

    .payment-btn {
        width: 100%;
        background: var(--primary-orange);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .payment-btn:hover {
        background: var(--primary-orange-dark);
        transform: translateY(-2px);
    }

    @media (max-width: 1400px) {
        .right-sidebar {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .right-sidebar.mobile-open {
            transform: translateX(0);
        }
    }
</style>

<aside class="right-sidebar">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="profile-name">{{ Auth::user()->name }}</div>
        <div class="profile-handle">@{{ Str::slug(Auth::user()->name) }}</div>
        
        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-label">Total order</div>
                <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total expenses</div>
                <div class="stat-value">${{ number_format($stats['total_expenses'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="cart-section">
        <div class="cart-header">
            <h3 class="cart-title">My Cart</h3>
            <button class="clear-cart-btn" onclick="clearCart()">Clear</button>
        </div>

        <div class="cart-items" id="cart-items">
            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach($cartItems as $item)
                <div class="cart-item">
                    <img src="{{ $item['product']->image ?? 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=200&h=200&fit=crop' }}" 
                         alt="{{ $item['product']->name }}" 
                         class="cart-item-image">
                    <div class="cart-item-info">
                        <div class="cart-item-name">{{ $item['product']->name }}</div>
                        <div class="cart-item-price">${{ number_format($item['product']->price, 2) }}</div>
                    </div>
                    <div class="cart-item-controls">
                        <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, -1)">-</button>
                        <span class="quantity">{{ $item['quantity'] }}</span>
                        <button class="quantity-btn" onclick="updateQuantity({{ $item['product']->id }}, 1)">+</button>
                    </div>
                </div>
                @endforeach
            @else
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem 0;">Your cart is empty</p>
            @endif
        </div>

        <div class="cart-total">
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-value" id="cart-total">${{ number_format($cartTotal ?? 0, 2) }}</span>
            </div>
        </div>

        <button class="payment-btn" onclick="window.location.href='{{ route('orders.checkout') }}'">
            Payment
        </button>
    </div>
</aside>
@endsection

@push('scripts')
<script>
    // Category filtering
    const categoryPills = document.querySelectorAll('.category-pill');
    const productCards = document.querySelectorAll('.product-card');

    categoryPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const category = pill.dataset.category;
            
            // Update active pill
            categoryPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            // Filter products
            productCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Add to cart
    function addToCart(productId) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update cart
            } else {
                alert(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add to cart');
        });
    }

    // Update quantity
    function updateQuantity(productId, change) {
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                change: change
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Clear cart
    function clearCart() {
        if (confirm('Are you sure you want to clear your cart?')) {
            fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush

