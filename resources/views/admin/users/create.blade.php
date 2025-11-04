@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'users'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">➕ Add User</h1>
    <p style="color: var(--text-secondary);">Create a new user account</p>
</div>

<!-- Form Card -->
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">User Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        Full Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
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
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="form-input" placeholder="user@example.com">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
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
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="chef" {{ old('role') == 'chef' ? 'selected' : '' }}>Chef</option>
                        <option value="delivery" {{ old('role') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" 
                              placeholder="Enter full address">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Account Password</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        Password
                        <span class="required">*</span>
                    </label>
                    <input type="password" name="password" required 
                           class="form-input" placeholder="••••••••">
                    <span class="form-help">Minimum 8 characters</span>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Confirm Password
                        <span class="required">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required 
                           class="form-input" placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Create User
            </button>
            <a href="{{ route('admin.users') }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection
