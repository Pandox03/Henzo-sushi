@extends('layouts.dashboard')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.orders') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span>Orders</span>
    </a>

    <a href="{{ route('admin.products') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <span>Products</span>
    </a>

    <a href="{{ route('admin.categories') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        <span>Categories</span>
    </a>

    <a href="{{ route('admin.users') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <span>Users</span>
    </a>

    <a href="{{ route('admin.promo-codes') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        <span>Promo Codes</span>
    </a>

    <a href="{{ route('admin.schedules') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Schedules</span>
    </a>

    <a href="{{ route('admin.settings') }}" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Settings</span>
    </a>
@endsection

@section('content')
<style>
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-orange);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.orange { background: rgba(255, 87, 34, 0.1); color: var(--primary-orange); }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-icon.purple { background: rgba(168, 85, 247, 0.1); color: #a855f7; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-change.positive { color: #22c55e; }
    .stat-change.negative { color: #ef4444; }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .chart-select {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
    }

    /* Tables */
    .table-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .view-all-link {
        color: var(--primary-orange);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-all-link:hover {
        text-decoration: underline;
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

    .data-table tr:last-child td {
        border-bottom: none;
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

    .status-badge.pending { background: rgba(251, 191, 36, 0.1); color: #fbbf24; }
    .status-badge.preparing { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.delivering { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
    .status-badge.delivered { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-badge.cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .quick-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: var(--border-color);
        color: var(--text-primary);
    }

    @media (max-width: 1024px) {
        .charts-section {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .data-table {
            font-size: 0.75rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Admin Dashboard</h1>
    <p style="color: var(--text-secondary);">Overview of your restaurant operations</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-icon orange">📋</div>
        </div>
        <div class="stat-change {{ $stats['orders_growth'] >= 0 ? 'positive' : 'negative' }}">
            @if($stats['orders_growth'] >= 0)
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            @else
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            @endif
            <span>{{ abs($stats['orders_growth']) }}% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-icon green">💰</div>
        </div>
        <div class="stat-change {{ $stats['revenue_growth'] >= 0 ? 'positive' : 'negative' }}">
            @if($stats['revenue_growth'] >= 0)
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            @else
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            @endif
            <span>{{ abs($stats['revenue_growth']) }}% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_customers'] }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-icon blue">👥</div>
        </div>
        <div class="stat-change {{ $stats['customers_growth'] >= 0 ? 'positive' : 'negative' }}">
            @if($stats['customers_growth'] >= 0)
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            @else
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            @endif
            <span>{{ abs($stats['customers_growth']) }}% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-icon purple">🍱</div>
        </div>
        <div class="stat-change">
            <span style="color: var(--text-secondary);">Active products</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Revenue Overview (Last 30 Days)</h3>
        </div>
        <div style="height: 300px; position: relative;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Order Status</h3>
        </div>
        <div style="padding: 1rem 0;">
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Pending</span>
                    <span style="font-weight: 600;">{{ $stats['pending_orders'] }}</span>
                </div>
                <div style="height: 8px; background: var(--bg-dark); border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $stats['total_orders'] > 0 ? ($stats['pending_orders'] / $stats['total_orders'] * 100) : 0 }}%; background: #fbbf24;"></div>
                </div>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Preparing</span>
                    <span style="font-weight: 600;">{{ $stats['preparing_orders'] }}</span>
                </div>
                <div style="height: 8px; background: var(--bg-dark); border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $stats['total_orders'] > 0 ? ($stats['preparing_orders'] / $stats['total_orders'] * 100) : 0 }}%; background: #3b82f6;"></div>
                </div>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Out for Delivery</span>
                    <span style="font-weight: 600;">{{ $stats['out_for_delivery'] }}</span>
                </div>
                <div style="height: 8px; background: var(--bg-dark); border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $stats['total_orders'] > 0 ? ($stats['out_for_delivery'] / $stats['total_orders'] * 100) : 0 }}%; background: #a855f7;"></div>
                </div>
            </div>
            
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Delivered</span>
                    <span style="font-weight: 600;">{{ $stats['delivered_orders'] }}</span>
                </div>
                <div style="height: 8px; background: var(--bg-dark); border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $stats['total_orders'] > 0 ? ($stats['delivered_orders'] / $stats['total_orders'] * 100) : 0 }}%; background: #22c55e;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="table-section">
    <div class="table-header">
        <h3 class="table-title">Recent Orders</h3>
        <a href="{{ route('admin.orders') }}" class="view-all-link">
            View All
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr>
                <td style="font-weight: 600;">#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td style="font-weight: 600;">${{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="status-badge {{ $order->status }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>
                <td style="color: var(--text-secondary);">{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="quick-actions">
                        <a href="{{ route('admin.orders.show', $order) }}" class="action-btn" title="View">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Top Products & Customers -->
<div class="charts-section">
    <div class="chart-card">
        <div class="table-header">
            <h3 class="table-title">Top Selling Products</h3>
            <a href="{{ route('admin.products') }}" class="view-all-link">
                View All
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($topProducts->count() > 0)
            @foreach($topProducts as $product)
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=200&h=200&fit=crop' }}" 
                     style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;" 
                     alt="{{ $product->name }}">
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $product->name }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $product->total_sold }} sold</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 600; color: var(--primary-orange);">${{ number_format($product->revenue, 2) }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Revenue</div>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                No products sold yet
            </div>
        @endif
    </div>

    <div class="chart-card">
        <div class="table-header">
            <h3 class="table-title">Top Customers</h3>
            <a href="{{ route('admin.users') }}" class="view-all-link">
                View All
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($topCustomers->count() > 0)
            @foreach($topCustomers as $customer)
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-orange); display: flex; align-items: center; justify-content: center; font-weight: 600; color: white;">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $customer->name }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $customer->total_orders }} orders</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 600; color: var(--primary-orange);">${{ number_format($customer->total_spent, 2) }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Total</div>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                No customers yet
            </div>
        @endif
    </div>
</div>

<!-- Staff Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_chefs'] }}</div>
                <div class="stat-label">Active Chefs</div>
            </div>
            <div class="stat-icon orange">👨‍🍳</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_delivery_guys'] }}</div>
                <div class="stat-label">Delivery Staff</div>
            </div>
            <div class="stat-icon blue">🚚</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($revenueData) !!},
                borderColor: '#FF5722',
                backgroundColor: 'rgba(255, 87, 34, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#FF5722',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#ffffff',
                    bodyColor: '#a0a0a0',
                    borderColor: '#2a2a2a',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: '#2a2a2a',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#a0a0a0',
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    grid: {
                        color: '#2a2a2a',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#a0a0a0',
                        callback: function(value) {
                            return '$' + value;
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

