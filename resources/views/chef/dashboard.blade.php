@extends('layouts.dashboard')

@section('sidebar-nav')
    <a href="{{ route('chef.dashboard') }}" class="nav-item active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('chef.dashboard') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span>New Orders</span>
    </a>

    <a href="{{ route('chef.dashboard') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Preparing</span>
    </a>

    <a href="{{ route('chef.orders.history') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span>Order History</span>
    </a>

    <a href="#" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <span>Menu Items</span>
    </a>

    <a href="#" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span>Statistics</span>
    </a>

    <a href="{{ route('profile.edit') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Settings</span>
    </a>
@endsection

@section('content')
<style>
    /* Chef Stats */
    .chef-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chef-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
    }

    .chef-stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
    }

    .chef-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .chef-stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Order Sections */
    .orders-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .order-count-badge {
        background: var(--primary-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Order Cards */
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .order-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-orange);
        box-shadow: 0 8px 24px rgba(255, 87, 34, 0.2);
    }

    .order-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .order-id {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .order-time {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
    }

    .customer-details h4 {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.125rem;
    }

    .customer-details p {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .order-items {
        margin-bottom: 1rem;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        font-size: 0.875rem;
    }

    .item-name {
        color: var(--text-primary);
    }

    .item-quantity {
        color: var(--text-secondary);
    }

    .order-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .order-total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-orange);
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-button {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-accept {
        background: var(--primary-orange);
        color: white;
    }

    .btn-accept:hover {
        background: var(--primary-orange-dark);
    }

    .btn-ready {
        background: #22c55e;
        color: white;
    }

    .btn-ready:hover {
        background: #16a34a;
    }

    .btn-view {
        background: var(--bg-dark);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-view:hover {
        background: var(--border-color);
    }

    .timer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-orange);
        font-weight: 600;
        font-size: 0.875rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .orders-grid {
            grid-template-columns: 1fr;
        }

        .chef-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">👨‍🍳 Chef Dashboard</h1>
    <p style="color: var(--text-secondary);">Manage and prepare customer orders</p>
</div>

<!-- Stats Cards -->
<div class="chef-stats">
    <div class="chef-stat-card">
        <div class="chef-stat-icon">⏳</div>
        <div class="chef-stat-value">{{ $awaitingOrders->count() }}</div>
        <div class="chef-stat-label">Awaiting Orders</div>
    </div>

    <div class="chef-stat-card">
        <div class="chef-stat-icon">👨‍🍳</div>
        <div class="chef-stat-value">{{ $chefOrders->count() }}</div>
        <div class="chef-stat-label">My Orders</div>
    </div>

    <div class="chef-stat-card">
        <div class="chef-stat-icon">✅</div>
        <div class="chef-stat-value">{{ $completedToday ?? 0 }}</div>
        <div class="chef-stat-label">Completed Today</div>
    </div>

    <div class="chef-stat-card">
        <div class="chef-stat-icon">⚡</div>
        <div class="chef-stat-value">{{ $averageTime ?? '15' }}min</div>
        <div class="chef-stat-label">Avg Prep Time</div>
    </div>
</div>

<!-- Awaiting Orders Section -->
@if($awaitingOrders->count() > 0)
<div class="orders-section">
    <div class="section-header">
        <h2 class="section-title">
            New Orders
            <span class="order-count-badge">{{ $awaitingOrders->count() }}</span>
        </h2>
    </div>

    <div class="orders-grid">
        @foreach($awaitingOrders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <span class="order-id">#{{ $order->id }}</span>
                <span class="order-time">{{ $order->created_at->diffForHumans() }}</span>
            </div>

            <div class="customer-info">
                <div class="customer-avatar">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div class="customer-details">
                    <h4>{{ $order->user->name }}</h4>
                    <p>{{ $order->user->phone ?? 'No phone' }}</p>
                </div>
            </div>

            <div class="order-items">
                @foreach($order->orderItems->take(3) as $item)
                <div class="order-item">
                    <span class="item-name">{{ $item->product->name }}</span>
                    <span class="item-quantity">x{{ $item->quantity }}</span>
                </div>
                @endforeach
                @if($order->orderItems->count() > 3)
                <div class="order-item">
                    <span style="color: var(--text-secondary); font-size: 0.75rem;">
                        +{{ $order->orderItems->count() - 3 }} more items
                    </span>
                </div>
                @endif
            </div>

            <div class="order-footer">
                <span class="order-total">${{ number_format($order->total_amount, 2) }}</span>
                <div class="order-actions">
                    <form action="{{ route('chef.orders.accept', $order) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="action-button btn-accept">Accept</button>
                    </form>
                    <a href="{{ route('chef.orders.show', $order) }}" class="action-button btn-view">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Preparing Orders Section -->
@if($chefOrders->count() > 0)
<div class="orders-section">
    <div class="section-header">
        <h2 class="section-title">
            Currently Preparing
            <span class="order-count-badge">{{ $chefOrders->count() }}</span>
        </h2>
    </div>

    <div class="orders-grid">
        @foreach($chefOrders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <span class="order-id">#{{ $order->id }}</span>
                <div class="timer">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $order->accepted_at ? $order->accepted_at->diffForHumans(null, true) : 'Just now' }}
                </div>
            </div>

            <div class="customer-info">
                <div class="customer-avatar">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div class="customer-details">
                    <h4>{{ $order->user->name }}</h4>
                    <p>{{ $order->user->address ?? 'No address' }}</p>
                </div>
            </div>

            <div class="order-items">
                @foreach($order->orderItems->take(3) as $item)
                <div class="order-item">
                    <span class="item-name">{{ $item->product->name }}</span>
                    <span class="item-quantity">x{{ $item->quantity }}</span>
                </div>
                @endforeach
                @if($order->orderItems->count() > 3)
                <div class="order-item">
                    <span style="color: var(--text-secondary); font-size: 0.75rem;">
                        +{{ $order->orderItems->count() - 3 }} more items
                    </span>
                </div>
                @endif
            </div>

            <div class="order-footer">
                <span class="order-total">${{ number_format($order->total_amount, 2) }}</span>
                <div class="order-actions">
                    <form action="{{ route('chef.orders.status', $order) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" class="action-button btn-ready">Mark Ready</button>
                    </form>
                    <a href="{{ route('chef.orders.show', $order) }}" class="action-button btn-view">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Empty State -->
@if($awaitingOrders->count() === 0 && $chefOrders->count() === 0)
<div class="empty-state">
    <div class="empty-state-icon">🍱</div>
    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">No Orders Yet</h3>
    <p>New orders will appear here when customers place them</p>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-refresh page every 30 seconds to check for new orders
    setInterval(() => {
        // Check for new orders without full page reload
        fetch('{{ route("chef.check-new-orders") }}')
            .then(response => response.json())
            .then(data => {
                if (data.hasNewOrders) {
                    // Play notification sound
                    const audio = new Audio('/sounds/notification.mp3');
                    audio.play().catch(e => console.log('Could not play sound'));
                    
                    // Show browser notification
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('New Order!', {
                            body: `You have ${data.newOrdersCount} new order(s)`,
                            icon: '/favicon.ico'
                        });
                    }
                    
                    // Reload page to show new orders
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => console.error('Error checking orders:', error));
    }, 30000);

    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
</script>
@endpush

