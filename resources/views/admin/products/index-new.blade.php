@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'products'])
@endsection

@section('content')
<style>
    /* Reuse styles from users page */
    .filter-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
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
        transition: all 0.2s ease;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-orange);
    }

    .form-input::placeholder {
        color: var(--text-secondary);
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
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: var(--border-color);
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
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

    .product-content {
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

    .product-category {
        font-size: 0.75rem;
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
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .availability-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .availability-badge.available {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .availability-badge.unavailable {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .product-actions {
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

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
        gap: 0.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background: var(--border-color);
    }

    .pagination .active {
        background: var(--primary-orange);
        border-color: var(--primary-orange);
        color: white;
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">🍣 Menu Management</h1>
        <p style="color: var(--text-secondary);">Manage your sushi menu and products</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Product
    </a>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.products') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Product name or description" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" value="{{ request('category') }}" placeholder="e.g., Sushi" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Availability</label>
                <select name="availability" class="form-select">
                    <option value="">All Products</option>
                    <option value="available" {{ request('availability')==='available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('availability')==='unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <div class="form-group" style="justify-content: flex-end;">
                <label class="form-label" style="opacity: 0;">Actions</label>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary">Filter</button>
                    <a href="{{ route('admin.products') }}" class="btn-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Products Grid -->
<div class="products-grid">
    @forelse($products as $product)
        <div class="product-card">
            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=600&h=400&fit=crop' }}" 
                 alt="{{ $product->name }}" 
                 class="product-image">
            <div class="product-content">
                <div class="product-header">
                    <div style="flex: 1;">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                    </div>
                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                </div>

                <div class="product-description">
                    {{ $product->description }}
                </div>

                <div class="product-footer">
                    <span class="availability-badge {{ $product->is_available ? 'available' : 'unavailable' }}">
                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                    </span>

                    <div class="product-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn" title="Edit">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('admin.products.delete', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Delete">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: var(--text-secondary);">
            <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🍱</div>
            <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">No Products Found</h3>
            <p>Add your first product to get started!</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products->hasPages())
    <div class="pagination">
        {{ $products->links() }}
    </div>
@endif
@endsection

