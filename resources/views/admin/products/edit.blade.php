@extends('layouts.dashboard')

@section('sidebar-nav')
    @include('admin.partials.sidebar-nav', ['active' => 'products'])
@endsection

@section('content')
@include('admin.partials.form-styles')

<!-- Page Header -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">✏️ Edit Product</h1>
    <p style="color: var(--text-secondary);">Update product information and pricing</p>
</div>

<!-- Form Card -->
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">Basic Information</h3>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">
                        Product Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                           class="form-input" placeholder="e.g., Salmon Nigiri">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">
                        Description
                        <span class="required">*</span>
                    </label>
                    <textarea name="description" required class="form-textarea" 
                              placeholder="Describe your product...">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Category
                        <span class="required">*</span>
                    </label>
                    <select name="category_id" required class="form-select">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Price (MAD)
                        <span class="required">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required 
                           class="form-input" placeholder="0.00">
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Product Image</label>
                    <div class="image-upload-preview" onclick="document.getElementById('image-input').click()">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div style="text-align: center; color: var(--text-secondary);">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 0.5rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p>Click to upload image</p>
                            </div>
                        @endif
                    </div>
                    <input type="file" id="image-input" name="image" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)">
                    @error('image')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Discount Settings (Optional)</h3>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="has_discount" value="1" 
                           {{ old('has_discount', $product->has_discount) ? 'checked' : '' }}
                           onchange="toggleDiscountFields()">
                    <span>Enable Discount</span>
                </label>
            </div>

            <div id="discount-fields" style="display: {{ old('has_discount', $product->has_discount) ? 'block' : 'none' }};">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-select">
                            <option value="percentage" {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (MAD)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Discount Value</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $product->discount_value) }}" 
                               step="0.01" min="0" class="form-input" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="discount_start" 
                               value="{{ old('discount_start', $product->discount_start ? $product->discount_start->format('Y-m-d\TH:i') : '') }}" 
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="discount_end" 
                               value="{{ old('discount_end', $product->discount_end ? $product->discount_end->format('Y-m-d\TH:i') : '') }}" 
                               class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Availability</h3>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_available" value="1" 
                           {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                    <span>Available for ordering</span>
                </label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Product
            </button>
            <a href="{{ route('admin.products') }}" class="btn-secondary">Cancel</a>
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

function toggleDiscountFields() {
    const checkbox = document.querySelector('input[name="has_discount"]');
    const fields = document.getElementById('discount-fields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}
</script>
@endsection
