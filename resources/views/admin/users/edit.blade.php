@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'users'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">✏️ Edit User</h1>
    <p style="color: var(--text-secondary);">Update user information</p>
</div>

<!-- Form Card -->
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">User Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        Full Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="form-input" placeholder="Enter full name">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Email Address
                        <span class="required">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                           class="form-input" placeholder="user@example.com">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="form-input" placeholder="+212 600 000 000">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Role
                        <span class="required">*</span>
                    </label>
                    <select name="role" required class="form-select">
                        <option value="">Select Role</option>
                        <option value="customer" {{ old('role', $user->getRoleNames()->first()) == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="chef" {{ old('role', $user->getRoleNames()->first()) == 'chef' ? 'selected' : '' }}>Chef</option>
                        <option value="delivery" {{ old('role', $user->getRoleNames()->first()) == 'delivery' ? 'selected' : '' }}>Delivery</option>
                        <option value="admin" {{ old('role', $user->getRoleNames()->first()) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" 
                              placeholder="Enter full address">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Change Password (Optional)</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1rem; font-size: 0.875rem;">Leave blank to keep current password</p>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" 
                           class="form-input" placeholder="••••••••">
                    <span class="form-help">Minimum 8 characters</span>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" 
                           class="form-input" placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update User
            </button>
            <a href="{{ route('admin.users') }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection
