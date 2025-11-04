@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'categories'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">➕ Add Category</h1>
    <p style="color: var(--text-secondary);">Create a new product category</p>
</div>

<!-- Form Card -->
<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">Category Information</h3>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">
                        Category Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="form-input" placeholder="Enter category name">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" 
                              placeholder="Enter category description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
                           min="0" class="form-input" placeholder="0">
                    <span class="form-help">Lower numbers appear first</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <label class="form-checkbox">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>Active (visible to customers)</span>
                    </label>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Category Image</label>
                    <div class="image-upload-preview" onclick="document.getElementById('image-input').click()">
                        <div style="text-align: center; color: var(--text-secondary);">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 0.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>Click to upload image</p>
                        </div>
                    </div>
                    <input type="file" id="image-input" name="image" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)">
                    @error('image')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Create Category
            </button>
            <a href="{{ route('admin.categories') }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.image-upload-preview');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
