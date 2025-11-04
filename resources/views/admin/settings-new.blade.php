@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'settings'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">⚙️ System Settings</h1>
    <p style="color: var(--text-secondary);">Configure your Henzo Sushi restaurant settings</p>
</div>

@if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

<!-- Restaurant Information -->
<div class="form-card">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="form-section">
            <h3 class="section-title">🏪 Restaurant Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Restaurant Name</label>
                    <input type="text" name="restaurant_name" value="Henzo Sushi" class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="restaurant_phone" value="+212 6XX XXX XXX" class="form-input">
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <textarea name="restaurant_address" class="form-textarea" rows="3">Casablanca, Morocco</textarea>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Email</label>
                    <input type="email" name="restaurant_email" value="contact@henzosushi.com" class="form-input">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Restaurant Info
            </button>
        </div>
    </form>
</div>

<!-- Delivery Settings -->
<div class="form-card">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="form-section">
            <h3 class="section-title">🚚 Delivery Settings</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Delivery Fee (MAD)</label>
                    <input type="number" name="delivery_fee" value="15.00" step="0.01" min="0" class="form-input">
                    <span class="form-help">Fixed delivery fee for all orders</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Free Delivery Minimum (MAD)</label>
                    <input type="number" name="free_delivery_min" value="200.00" step="0.01" min="0" class="form-input">
                    <span class="form-help">Orders above this amount get free delivery</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Delivery Radius (km)</label>
                    <input type="number" name="delivery_radius" value="10" min="1" class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Estimated Delivery Time (min)</label>
                    <input type="number" name="delivery_time" value="45" min="1" class="form-input">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Delivery Settings
            </button>
        </div>
    </form>
</div>

<!-- Order Settings -->
<div class="form-card">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="form-section">
            <h3 class="section-title">📦 Order Settings</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Minimum Order Amount (MAD)</label>
                    <input type="number" name="min_order_amount" value="50.00" step="0.01" min="0" class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="0" step="0.01" min="0" max="100" class="form-input">
                </div>
                
                <div class="form-group full-width">
                    <label class="form-checkbox">
                        <input type="checkbox" name="allow_pickup" value="1" checked>
                        <span>Allow pickup orders</span>
                    </label>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-checkbox">
                        <input type="checkbox" name="auto_accept_orders" value="1">
                        <span>Automatically accept orders (skip pending status)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Order Settings
            </button>
        </div>
    </form>
</div>

<!-- Notification Settings -->
<div class="form-card">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="form-section">
            <h3 class="section-title">🔔 Notification Settings</h3>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-checkbox">
                        <input type="checkbox" name="notify_new_orders" value="1" checked>
                        <span>Email notifications for new orders</span>
                    </label>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-checkbox">
                        <input type="checkbox" name="notify_low_stock" value="1" checked>
                        <span>Email notifications for low stock items</span>
                    </label>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Notification Email</label>
                    <input type="email" name="notification_email" value="admin@henzosushi.com" class="form-input">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Notification Settings
            </button>
        </div>
    </form>
</div>
@endsection

