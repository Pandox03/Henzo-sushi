@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'orders'])
@endsection

@section('content')
<style>
    .filter-card, .table-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-input, .form-select {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-orange);
    }

    .btn-primary {
        background: var(--primary-orange);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: var(--primary-orange-dark);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--bg-dark);
        color: var(--text-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: 1px solid var(--border-color);
        cursor: pointer;
        text-decoration: none;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.875rem;
    }

    .data-table tr:hover {
        background: rgba(255, 87, 34, 0.05);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.pending { background: rgba(234, 179, 8, 0.1); color: #eab308; }
    .status-badge.preparing { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.ready { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-badge.out_for_delivery { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
    .status-badge.delivered { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-badge.cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--border-color);
        color: var(--text-primary);
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">📋 Order Management</h1>
    <p style="color: var(--text-secondary);">Monitor and manage all restaurant orders</p>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.orders') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(['pending' => 'Pending', 'preparing' => 'Preparing', 'ready' => 'Ready', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Customer</label>
            <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Name or email" class="form-input">
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.orders') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="table-section">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">All Orders ({{ $orders->total() }})</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Chef</th>
                <th>Delivery</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td style="font-weight: 600; color: var(--primary-orange);">
                        #{{ $order->id }}
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $order->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $order->user->phone }}</div>
                    </td>
                    <td>
                        <span class="status-badge {{ $order->status }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $order->chef ? $order->chef->name : '—' }}
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $order->deliveryGuy ? $order->deliveryGuy->name : '—' }}
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        {{ number_format($order->total_amount, 2) }} MAD
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="action-btn" title="View Details">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        No orders found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

