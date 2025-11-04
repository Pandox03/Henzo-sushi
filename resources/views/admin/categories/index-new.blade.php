@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'categories'])
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

    .form-input {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-input:focus {
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

    .status-badge.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .status-badge.inactive {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
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
        text-decoration: none;
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
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">📂 Categories</h1>
        <p style="color: var(--text-secondary);">Manage product categories and organization</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Category
    </a>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.categories') }}" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="flex: 1;">
            <label class="form-label">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Category name" class="form-input">
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.categories') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Categories Table -->
<div class="table-section">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">All Categories ({{ $categories->count() }})</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Status</th>
                <th>Sort Order</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        {{ $category->name }}
                    </td>
                    <td>
                        <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $category->sort_order }}
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        {{ $category->products->count() }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn" title="Edit">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.categories.delete', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will affect {{ $category->products->count() }} products.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        No categories found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

