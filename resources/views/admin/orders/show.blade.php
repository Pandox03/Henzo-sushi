@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'orders'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<style>
    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
    }

    .info-value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--bg-dark);
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }

    .order-item:last-child {
        margin-bottom: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-badge.pending { background: rgba(234, 179, 8, 0.1); color: #eab308; }
    .status-badge.preparing { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.ready { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-badge.out_for_delivery { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
    .status-badge.delivered { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-badge.cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .grid-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 87, 34, 0.1);
        border-radius: 10px;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-orange);
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">📋 Order #{{ $order->id }}</h1>
        <p style="color: var(--text-secondary);">Order details and management</p>
    </div>
    <a href="{{ route('admin.orders') }}" class="btn-secondary">
        ← Back to Orders
    </a>
</div>

<div class="grid-2">
    <!-- Main Content -->
    <div>
        <!-- Order Information -->
        <div class="info-card" style="margin-bottom: 2rem;">
            <div class="section-title">
                📋 Order Information
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Order ID</span>
                    <span class="info-value" style="color: var(--primary-orange); font-weight: 600;">#{{ $order->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span>
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display: inline;">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="form-select" style="padding: 0.5rem 1rem;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>👨‍🍳 Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>✅ Ready</option>
                                <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🎉 Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                            </select>
                        </form>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">{{ ucfirst($order->payment_method ?? 'Cash') }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="info-card" style="margin-bottom: 2rem;">
            <div class="section-title">
                🍣 Order Items
            </div>

            <div>
                @foreach($order->orderItems as $item)
                    <div class="order-item">
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">
                                {{ $item->product->name }}
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-secondary);">
                                {{ number_format($item->unit_price, 2) }} MAD × {{ $item->quantity }}
                            </div>
                        </div>
                        <div style="font-weight: 600; color: var(--text-primary);">
                            {{ number_format($item->total_price, 2) }} MAD
                        </div>
                    </div>
                @endforeach

                <div class="total-row" style="margin-top: 1rem;">
                    <span>Total Amount</span>
                    <span>{{ number_format($order->total_amount, 2) }} MAD</span>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="info-card">
            <div class="section-title">
                📍 Delivery Information
            </div>

            <div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value">{{ $order->delivery_address }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->phone }}</span>
                </div>
                @if($order->notes)
                    <div class="info-row">
                        <span class="info-label">Notes</span>
                        <span class="info-value">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Customer Information -->
        <div class="info-card" style="margin-bottom: 2rem;">
            <div class="section-title">
                👤 Customer
            </div>

            <div>
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $order->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->user->phone }}</span>
                </div>
            </div>
        </div>

        <!-- Staff Assignment -->
        <div class="info-card">
            <div class="section-title">
                👥 Staff Assignment
            </div>

            <div>
                <div class="info-row">
                    <span class="info-label">👨‍🍳 Chef</span>
                    <span class="info-value">{{ $order->chef ? $order->chef->name : 'Not assigned' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🚚 Delivery</span>
                    <span class="info-value">{{ $order->deliveryGuy ? $order->deliveryGuy->name : 'Not assigned' }}</span>
                </div>
            </div>

            @if($order->accepted_at)
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Timeline</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.8;">
                        @if($order->accepted_at)
                            <div>✅ Accepted: {{ $order->accepted_at->format('H:i') }}</div>
                        @endif
                        @if($order->ready_at)
                            <div>🍱 Ready: {{ $order->ready_at->format('H:i') }}</div>
                        @endif
                        @if($order->out_for_delivery_at)
                            <div>🚚 Out: {{ $order->out_for_delivery_at->format('H:i') }}</div>
                        @endif
                        @if($order->delivered_at)
                            <div>🎉 Delivered: {{ $order->delivered_at->format('H:i') }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
