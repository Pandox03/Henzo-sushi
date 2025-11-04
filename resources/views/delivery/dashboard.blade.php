@extends('layouts.dashboard')

@section('sidebar-nav')
    <a href="{{ route('delivery.dashboard') }}" class="nav-item active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('delivery.dashboard') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span>Assigned Orders</span>
    </a>

    <a href="{{ route('delivery.history') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Delivery History</span>
    </a>

    <a href="#" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
        </svg>
        <span>Route Map</span>
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
    /* Delivery Stats */
    .delivery-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .delivery-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
    }

    .delivery-stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
    }

    .delivery-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .delivery-stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Delivery Cards */
    .delivery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .delivery-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .delivery-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-orange);
        box-shadow: 0 8px 24px rgba(255, 87, 34, 0.2);
    }

    .delivery-card-header {
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

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.out_for_delivery {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
    }

    .status-badge.delivered {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .customer-section {
        margin-bottom: 1rem;
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

    .address-section {
        background: rgba(255, 87, 34, 0.05);
        border: 1px solid rgba(255, 87, 34, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .address-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        color: var(--primary-orange);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .address-text {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .order-details {
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

    .delivery-footer {
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

    .delivery-actions {
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-navigate {
        background: #3b82f6;
        color: white;
    }

    .btn-navigate:hover {
        background: #2563eb;
    }

    .btn-delivered {
        background: #22c55e;
        color: white;
    }

    .btn-delivered:hover {
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
        .delivery-grid {
            grid-template-columns: 1fr;
        }

        .delivery-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">🚚 Delivery Dashboard</h1>
    <p style="color: var(--text-secondary);">Manage your delivery orders and routes</p>
</div>

<!-- Stats Cards -->
<div class="delivery-stats">
    <div class="delivery-stat-card">
        <div class="delivery-stat-icon">📦</div>
        <div class="delivery-stat-value">{{ $todayStats['total_orders'] }}</div>
        <div class="delivery-stat-label">Today's Orders</div>
    </div>

    <div class="delivery-stat-card">
        <div class="delivery-stat-icon">✅</div>
        <div class="delivery-stat-value">{{ $todayStats['delivered_orders'] }}</div>
        <div class="delivery-stat-label">Completed</div>
    </div>

    <div class="delivery-stat-card">
        <div class="delivery-stat-icon">🚚</div>
        <div class="delivery-stat-value">{{ $todayStats['pending_orders'] }}</div>
        <div class="delivery-stat-label">In Progress</div>
    </div>

    <div class="delivery-stat-card">
        <div class="delivery-stat-icon">💵</div>
        <div class="delivery-stat-value">${{ number_format($earnings ?? 0, 2) }}</div>
        <div class="delivery-stat-label">Today's Earnings</div>
    </div>
</div>

<!-- Active Deliveries -->
@if($assignedOrders->where('status', 'out_for_delivery')->count() > 0)
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        Active Deliveries
        <span style="background: var(--primary-orange); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
            {{ $assignedOrders->where('status', 'out_for_delivery')->count() }}
        </span>
    </h2>

    <div class="delivery-grid">
        @foreach($assignedOrders->where('status', 'out_for_delivery') as $order)
        <div class="delivery-card">
            <div class="delivery-card-header">
                <span class="order-id">#{{ $order->id }}</span>
                <span class="status-badge out_for_delivery">Out for Delivery</span>
            </div>

            <div class="customer-section">
                <div class="customer-info">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div class="customer-details">
                        <h4>{{ $order->user->name }}</h4>
                        <p>{{ $order->user->phone ?? 'No phone' }}</p>
                    </div>
                </div>

                <div class="address-section">
                    <div class="address-header">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        Delivery Address
                    </div>
                    <div class="address-text">
                        {{ $order->user->address ?: 'Address not provided' }}
                    </div>
                </div>
            </div>

            <div class="order-details">
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

            <div class="delivery-footer">
                <span class="order-total">${{ number_format($order->total_amount, 2) }}</span>
                <div class="delivery-actions">
                    <a href="{{ route('delivery.orders.navigation', $order) }}" class="action-button btn-navigate">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Navigate
                    </a>
                    <form action="{{ route('delivery.orders.delivered', $order) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="action-button btn-delivered">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Delivered
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Completed Deliveries Today -->
@if($assignedOrders->where('status', 'delivered')->count() > 0)
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        Completed Today
        <span style="background: #22c55e; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
            {{ $assignedOrders->where('status', 'delivered')->count() }}
        </span>
    </h2>

    <div class="delivery-grid">
        @foreach($assignedOrders->where('status', 'delivered')->take(6) as $order)
        <div class="delivery-card">
            <div class="delivery-card-header">
                <span class="order-id">#{{ $order->id }}</span>
                <span class="status-badge delivered">Delivered</span>
            </div>

            <div class="customer-section">
                <div class="customer-info">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div class="customer-details">
                        <h4>{{ $order->user->name }}</h4>
                        <p>Delivered {{ $order->delivered_at ? $order->delivered_at->diffForHumans() : 'recently' }}</p>
                    </div>
                </div>
            </div>

            <div class="order-details">
                @foreach($order->orderItems->take(2) as $item)
                <div class="order-item">
                    <span class="item-name">{{ $item->product->name }}</span>
                    <span class="item-quantity">x{{ $item->quantity }}</span>
                </div>
                @endforeach
            </div>

            <div class="delivery-footer">
                <span class="order-total">${{ number_format($order->total_amount, 2) }}</span>
                <div class="delivery-actions">
                    <a href="{{ route('delivery.orders.show', $order) }}" class="action-button btn-view">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Empty State -->
@if($assignedOrders->count() === 0)
<div class="empty-state">
    <div class="empty-state-icon">📦</div>
    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">No Deliveries Assigned</h3>
    <p>You don't have any deliveries assigned yet. Check back later!</p>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-refresh every 60 seconds for delivery updates
    setInterval(() => {
        location.reload();
    }, 60000);
</script>
@endpush

