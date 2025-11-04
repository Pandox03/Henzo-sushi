<x-app-layout>
    <!-- Product Detail Hero -->
    <section class="product-detail-hero">
        <div class="container-detail">
            <div class="breadcrumb-nav">
                <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
                <svg class="breadcrumb-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('products.index') }}" class="breadcrumb-link">Menu</a>
                <svg class="breadcrumb-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="breadcrumb-current">{{ $product->name }}</span>
            </div>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container-detail">
            <div class="product-detail-grid">
                <!-- Product Images -->
                <div class="product-images-col">
                    <div class="product-main-image-wrapper">
                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1579584425555-c3ce17fd4351', '1583623025817-d180a2221d0a', '1564489563601-c53e96a14d2f', '1574071318508-1cdbab80d002', '1579027989536-46b295e8c8c1', '1582878826629-29b7ad1cdc43'][$product->id % 6]) . '?w=800&h=600&fit=crop' }}" 
                             alt="{{ $product->name }}" 
                             class="product-main-image">
                        <div class="image-overlay-badge">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span>Premium Quality</span>
                        </div>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="product-info-col">
                    <div class="product-category-badge">{{ $product->category->name }}</div>
                    <h1 class="product-detail-title">{{ $product->name }}</h1>
                    <div class="product-rating">
                        <div class="stars">
                            <svg class="star" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="star" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="star" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="star" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="star" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <span class="rating-text">(4.9) 127 reviews</span>
                    </div>
                    
                    <p class="product-detail-desc">{{ $product->description }}</p>
                    
                    <!-- Ingredients -->
                    <div class="ingredients-card">
                        <h3 class="ingredients-title">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            Ingredients
                        </h3>
                        <div class="ingredients-tags">
                            @if(str_contains(strtolower($product->name), 'salmon'))
                                <span class="ingredient-tag">🐟 Fresh Salmon</span>
                                <span class="ingredient-tag">🍚 Sushi Rice</span>
                                <span class="ingredient-tag">🟢 Nori Seaweed</span>
                                <span class="ingredient-tag">🌿 Wasabi</span>
                                <span class="ingredient-tag">🥢 Soy Sauce</span>
                            @elseif(str_contains(strtolower($product->name), 'tuna'))
                                <span class="ingredient-tag">🐟 Premium Tuna</span>
                                <span class="ingredient-tag">🍚 Sushi Rice</span>
                                <span class="ingredient-tag">🟢 Nori Seaweed</span>
                                <span class="ingredient-tag">🌿 Ginger</span>
                            @elseif(str_contains(strtolower($product->name), 'eel'))
                                <span class="ingredient-tag">🐠 Grilled Eel</span>
                                <span class="ingredient-tag">🍯 Sweet Sauce</span>
                                <span class="ingredient-tag">🍚 Sushi Rice</span>
                                <span class="ingredient-tag">🥒 Cucumber</span>
                            @elseif(str_contains(strtolower($product->name), 'roll'))
                                <span class="ingredient-tag">🍚 Rice</span>
                                <span class="ingredient-tag">🟢 Nori</span>
                                <span class="ingredient-tag">🐟 Fresh Fish</span>
                                <span class="ingredient-tag">🥬 Vegetables</span>
                                <span class="ingredient-tag">🌾 Sesame</span>
                            @else
                                <span class="ingredient-tag">✨ Fresh Ingredients</span>
                                <span class="ingredient-tag">👨‍🍳 Traditional Recipe</span>
                                <span class="ingredient-tag">🎌 Authentic Japanese</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Price & Info Card -->
                    <div class="price-info-card">
                        <div class="price-section">
                            <span class="price-label">Price</span>
                            <span class="product-detail-price">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="divider-vertical"></div>
                        <div class="prep-time-section">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="prep-label">Prep Time</span>
                                <span class="prep-value">{{ $product->preparation_time ?? '15' }} min</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add to Cart Section -->
                    <div class="add-to-cart-section-detail">
                        <div class="quantity-selector-detail">
                            <label class="quantity-label">Quantity</label>
                            <div class="quantity-controls">
                                <button class="quantity-btn-detail" onclick="decreaseQuantity()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="quantity-display-detail" id="quantity">1</span>
                                <button class="quantity-btn-detail" onclick="increaseQuantity()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button class="add-to-cart-btn-detail" onclick="addToCart()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>Add to Cart - $<span id="total-price">{{ number_format($product->price, 2) }}</span></span>
                        </button>
                    </div>
                    
                    <!-- Features -->
                    <div class="product-features">
                        <div class="feature-item">
                            <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Freshly Prepared</span>
                        </div>
                        <div class="feature-item">
                            <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="feature-item">
                            <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span>Quality Guaranteed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="related-products-section-modern">
        <div class="container-detail">
            <div class="related-header">
                <h2 class="related-title-modern">You May Also Like</h2>
                <p class="related-subtitle">Handpicked items that complement your selection</p>
            </div>
            <div class="related-grid-modern">
                @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('products.show', $relatedProduct) }}" class="related-card-modern">
                    <div class="related-image-wrapper">
                        <img src="{{ $relatedProduct->image ?: 'https://images.unsplash.com/photo-' . (['1579584425555-c3ce17fd4351', '1583623025817-d180a2221d0a', '1564489563601-c53e96a14d2f', '1574071318508-1cdbab80d002', '1579027989536-46b295e8c8c1', '1582878826629-29b7ad1cdc43'][$relatedProduct->id % 6]) . '?w=400&h=300&fit=crop' }}" 
                             alt="{{ $relatedProduct->name }}" 
                             loading="lazy">
                    </div>
                    <div class="related-content">
                        <h4 class="related-product-name">{{ $relatedProduct->name }}</h4>
                        <p class="related-product-desc">{{ Str::limit($relatedProduct->description, 60) }}</p>
                        <div class="related-footer">
                            <span class="related-price">${{ number_format($relatedProduct->price, 2) }}</span>
                            <span class="related-view-link">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <style>
        :root {
            --primary: #d4af37;
            --primary-dark: #b8941f;
            --text-dark: #1a202c;
            --text-light: #64748b;
        }

        /* Product Detail Hero */
        .product-detail-hero {
            background: white;
            padding: 7rem 2rem 2rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .container-detail {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .breadcrumb-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.9375rem;
            transition: color 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: var(--primary);
        }

        .breadcrumb-arrow {
            width: 1rem;
            height: 1rem;
            color: var(--text-light);
        }

        .breadcrumb-current {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9375rem;
        }

        /* Product Detail Section */
        .product-detail-section {
            background: white;
            padding: 3rem 0 5rem;
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        .product-images-col {
            position: sticky;
            top: 120px;
        }

        .product-main-image-wrapper {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            background: #f8fafc;
        }

        .product-main-image {
            width: 100%;
            height: auto;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
        }

        .image-overlay-badge {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--primary);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .product-info-col {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .product-category-badge {
            display: inline-block;
            width: fit-content;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(244, 208, 63, 0.1) 100%);
            color: var(--primary);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .product-detail-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--text-dark);
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stars {
            display: flex;
            gap: 0.25rem;
        }

        .star {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }

        .rating-text {
            color: var(--text-light);
            font-size: 0.9375rem;
        }

        .product-detail-desc {
            font-size: 1.125rem;
            line-height: 1.8;
            color: var(--text-light);
        }

        /* Ingredients Card */
        .ingredients-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
        }

        .ingredients-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .ingredients-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .ingredient-tag {
            background: white;
            color: var(--text-dark);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-size: 0.9375rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .ingredient-tag:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        /* Price Info Card */
        .price-info-card {
            display: flex;
            align-items: center;
            gap: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 2rem;
            border: 2px solid #e2e8f0;
        }

        .price-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .price-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .product-detail-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .divider-vertical {
            width: 1px;
            height: 60px;
            background: #e2e8f0;
        }

        .prep-time-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .prep-time-section svg {
            color: var(--primary);
        }

        .prep-time-section > div {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .prep-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 600;
        }

        .prep-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Add to Cart Section */
        .add-to-cart-section-detail {
            display: flex;
            gap: 1.5rem;
            align-items: end;
        }

        .quantity-selector-detail {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .quantity-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border-radius: 16px;
            padding: 0.5rem;
            border: 2px solid #e2e8f0;
        }

        .quantity-btn-detail {
            background: white;
            border: 1px solid #e2e8f0;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-dark);
        }

        .quantity-btn-detail:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: scale(1.05);
        }

        .quantity-display-detail {
            min-width: 60px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .add-to-cart-btn-detail {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            border: none;
            padding: 1.25rem 2.5rem;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.3);
        }

        .add-to-cart-btn-detail:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(212, 175, 55, 0.4);
        }

        /* Product Features */
        .product-features {
            display: flex;
            gap: 2rem;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9375rem;
        }

        .feature-icon {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary);
        }

        /* Related Products */
        .related-products-section-modern {
            background: #f8fafc;
            padding: 5rem 0;
        }

        .related-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .related-title-modern {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .related-subtitle {
            font-size: 1.125rem;
            color: var(--text-light);
        }

        .related-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .related-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.4s ease;
            border: 1px solid #f1f5f9;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }

        .related-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            border-color: var(--primary);
        }

        .related-image-wrapper {
            height: 200px;
            overflow: hidden;
            background: #f8fafc;
        }

        .related-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .related-card-modern:hover .related-image-wrapper img {
            transform: scale(1.1);
        }

        .related-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .related-product-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .related-product-desc {
            color: var(--text-light);
            font-size: 0.9375rem;
            margin-bottom: 1rem;
            flex: 1;
        }

        .related-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .related-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .related-view-link {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.875rem;
            transition: gap 0.3s ease;
        }

        .related-card-modern:hover .related-view-link {
            gap: 0.625rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .product-images-col {
                position: relative;
                top: 0;
            }

            .price-info-card {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .divider-vertical {
                width: 100%;
                height: 1px;
            }

            .prep-time-section {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .product-detail-hero {
                padding: 5rem 1rem 1.5rem;
            }

            .container-detail {
                padding: 0 1rem;
            }

            .product-detail-title {
                font-size: 2rem;
            }

            .add-to-cart-section-detail {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity-selector-detail {
                align-items: center;
            }

            .product-features {
                flex-direction: column;
                gap: 1rem;
            }

            .related-grid-modern {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .product-detail-price {
                font-size: 2.5rem;
            }

            .add-to-cart-btn-detail {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }
    </style>

    <script>
        let quantity = 1;
        const basePrice = {{ $product->price }};
        const productId = {{ $product->id }};

        function increaseQuantity() {
            quantity++;
            updateQuantity();
        }

        function decreaseQuantity() {
            if (quantity > 1) {
                quantity--;
                updateQuantity();
            }
        }

        function updateQuantity() {
            document.getElementById('quantity').textContent = quantity;
            const totalPrice = (basePrice * quantity).toFixed(2);
            document.getElementById('total-price').textContent = totalPrice;
        }

        function addToCart() {
            const btn = document.querySelector('.add-to-cart-btn-detail');
            const originalHTML = btn.innerHTML;
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success state
                    btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><span>Added to Cart!</span>';
                    btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                    // Update cart count
                    const cartCount = document.getElementById('cart-count');
                    const cartCountMobile = document.getElementById('cart-count-mobile');
                    
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                        cartCount.style.display = data.cart_count > 0 ? 'flex' : 'none';
                    }
                    if (cartCountMobile) {
                        cartCountMobile.textContent = data.cart_count;
                        cartCountMobile.style.display = data.cart_count > 0 ? 'flex' : 'none';
                    }
                    
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.style.background = 'linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%)';
                        quantity = 1;
                        updateQuantity();
                    }, 2500);
                } else {
                    alert(data.message || 'Failed to add item to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add item to cart. Please try again.');
            });
        }
    </script>
</x-app-layout>
