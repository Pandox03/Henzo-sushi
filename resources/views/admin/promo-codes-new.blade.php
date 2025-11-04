@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'promo-codes'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<style>
    .table-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
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

    .promo-code {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: rgba(255, 87, 34, 0.1);
        border: 2px dashed var(--primary-orange);
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: var(--primary-orange);
        letter-spacing: 0.05em;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .status-badge.inactive {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .status-badge.expired {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .action-buttons {
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

    .action-btn.delete {
        color: #ef4444;
    }

    .action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    .usage-bar {
        width: 100px;
        height: 8px;
        background: var(--bg-dark);
        border-radius: 4px;
        overflow: hidden;
    }

    .usage-fill {
        height: 100%;
        background: var(--primary-orange);
        transition: width 0.3s ease;
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">🎟️ Promo Codes</h1>
        <p style="color: var(--text-secondary);">Create and manage discount codes and special offers</p>
    </div>
    <button onclick="alert('Create promo code modal would open here')" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create Promo Code
    </button>
</div>

@if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

<!-- Promo Codes Table -->
<div class="table-section">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">All Promo Codes</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Discount</th>
                <th>Usage</th>
                <th>Valid Until</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($promoCodes ?? [] as $promo)
                <tr>
                    <td>
                        <span class="promo-code">{{ $promo->code }}</span>
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        {{ $promo->name }}
                    </td>
                    <td style="color: var(--primary-orange); font-weight: 600;">
                        @if($promo->discount_type === 'percentage')
                            {{ $promo->discount_value }}%
                        @else
                            {{ number_format($promo->discount_value, 2) }} MAD
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="usage-bar">
                                @php
                                    $percentage = $promo->max_uses > 0 ? ($promo->used_count / $promo->max_uses) * 100 : 0;
                                @endphp
                                <div class="usage-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">
                                {{ $promo->used_count }} / {{ $promo->max_uses ?? '∞' }}
                            </span>
                        </div>
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $promo->valid_until ? $promo->valid_until->format('M d, Y') : 'No expiry' }}
                    </td>
                    <td>
                        @php
                            $isExpired = $promo->valid_until && $promo->valid_until->isPast();
                            $isMaxedOut = $promo->max_uses && $promo->used_count >= $promo->max_uses;
                        @endphp
                        @if($isExpired || $isMaxedOut)
                            <span class="status-badge expired">Expired</span>
                        @elseif($promo->is_active)
                            <span class="status-badge active">Active</span>
                        @else
                            <span class="status-badge inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn" title="Edit" onclick="alert('Edit promo: {{ $promo->code }}')">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="action-btn delete" title="Delete" onclick="if(confirm('Delete this promo code?')) alert('Deleted')">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        No promo codes found. Create your first one!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

