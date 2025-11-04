<x-app-layout>
    <!-- Products Page Header -->
    <section class="products-page-header">
        <div class="container-products">
            <div class="page-header-content">
                <h1 class="page-title">Our Menu</h1>
                <p class="page-subtitle">Explore our authentic Japanese cuisine, crafted fresh daily by master chefs</p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-main-section">
        <div class="container-products">
            <!-- Category Filter -->
            <div class="category-filter-section">
                <div class="filter-header">
                    <h2 class="filter-title">Browse by Category</h2>
                    <p class="filter-subtitle">Select a category to filter dishes</p>
                </div>
            <div class="category-pills-wrapper">
                <button class="category-pill-modern active" data-category="all">
                    <span class="pill-text">All Dishes</span>
                    <span class="pill-count">{{ $products->count() }}</span>
                </button>
                @foreach($categories as $category)
                <button class="category-pill-modern" data-category="{{ $category->name }}">
                    <span class="pill-text">{{ $category->name }}</span>
                    <span class="pill-count">{{ $category->products->count() }}</span>
                </button>
                @endforeach
            </div>
            </div>

            @if($products->count() > 0)
                @foreach($categories as $category)
                <div class="category-section-modern" data-category-name="{{ $category->name }}">
                    <div class="category-header-clean">
                        <div class="category-title-wrapper">
                            <div>
                                <h3 class="category-name-clean">{{ $category->name }}</h3>
                                <p class="category-description-clean">
                                    @if($category->name === 'Nigiri')
                                        Hand-pressed sushi with premium fish on seasoned rice
                                    @elseif($category->name === 'Maki Rolls')
                                        Rolled sushi wrapped in crisp nori seaweed
                                    @elseif($category->name === 'Sashimi')
                                        Fresh, delicate slices of the finest seasonal fish
                                    @elseif($category->name === 'Appetizers')
                                        Traditional Japanese starters to begin your meal
                                    @else
                                        Carefully prepared {{ $category->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="category-item-count">{{ $category->products->count() }} items</span>
                    </div>
                    
                    <div class="products-list-clean">
                        @foreach($category->products as $product)
                        <div class="product-item-clean">
                            <div class="product-image-clean">
                                <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1579584425555-c3ce17fd4351', '1583623025817-d180a2221d0a', '1564489563601-c53e96a14d2f', '1574071318508-1cdbab80d002', '1579027989536-46b295e8c8c1', '1582878826629-29b7ad1cdc43'][$loop->index % 6]) . '?w=400&h=300&fit=crop' }}" 
                                     alt="{{ $product->name }}" 
                                     loading="lazy">
                                @if($loop->first)
                                <span class="product-badge-popular">Popular</span>
                                @endif
                            </div>
                            
                            <div class="product-details-clean">
                                <div class="product-header-row">
                                    <div class="product-info-left">
                                        <h4 class="product-name-clean">{{ $product->name }}</h4>
                                        <p class="product-desc-clean">{{ $product->description }}</p>
                                        <div class="product-meta-clean">
                                            <span class="meta-badge">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $product->preparation_time ?? '15' }} min
                                            </span>
                                            <span class="meta-badge">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Fresh
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="product-actions-right">
                                        <span class="product-price-large">${{ number_format($product->price, 2) }}</span>
                                        <div class="product-buttons">
                                            <a href="{{ route('products.show', $product) }}" class="btn-view-details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Details</span>
                                            </a>
                                            <button class="btn-add-cart" data-product-id="{{ $product->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                                <span>Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-products-state">
                    <svg class="no-products-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="no-products-title">No products found</h3>
                    <p class="no-products-text">Check back later for new delicious items.</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        :root {
            --primary: #d4af37;
            --primary-dark: #b8941f;
            --text-dark: #1a202c;
            --text-light: #64748b;
        }

        /* Page Header */
        .products-page-header {
            background: white;
            padding: 6rem 2rem 3rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .container-products {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-header-content {
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: var(--text-light);
            line-height: 1.7;
        }

        /* Products Section */
        .products-main-section {
            background: #fafbfc;
            padding: 3rem 0 5rem;
        }

        /* Category Filter */
        .category-filter-section {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 3rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .filter-header {
            margin-bottom: 2rem;
        }

        .filter-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .filter-subtitle {
            color: var(--text-light);
            font-size: 1rem;
        }

        .category-pills-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .category-pill-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.875rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .category-pill-modern:hover {
            border-color: var(--primary);
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
        }

        .category-pill-modern.active {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            border-color: var(--primary);
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .pill-text {
            font-weight: 600;
            font-size: 0.9375rem;
        }

        .pill-count {
            background: rgba(0,0,0,0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8125rem;
            font-weight: 700;
            min-width: 1.5rem;
            text-align: center;
        }

        .category-pill-modern.active .pill-count {
            background: rgba(15, 23, 42, 0.2);
        }

        /* Category Section */
        .category-section-modern {
            margin-bottom: 4rem;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .category-section-modern.hidden {
            display: none;
        }

        .category-header-clean {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .category-title-wrapper {
            display: flex;
            align-items: center;
        }

        .category-name-clean {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.375rem;
        }

        .category-description-clean {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.5;
        }

        .category-item-count {
            background: #f8fafc;
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            color: var(--text-light);
            font-size: 0.9375rem;
            border: 1px solid #e2e8f0;
        }

        /* Products List - Clean Layout */
        .products-list-clean {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .product-item-clean {
            display: flex;
            gap: 2rem;
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .product-item-clean:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .product-image-clean {
            flex-shrink: 0;
            width: 200px;
            height: 150px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }

        .product-image-clean img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-item-clean:hover .product-image-clean img {
            transform: scale(1.08);
        }

        .product-badge-popular {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: var(--primary);
            color: #0f172a;
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.4);
        }

        .product-details-clean {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-header-row {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
        }

        .product-info-left {
            flex: 1;
        }

        .product-name-clean {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.625rem;
            line-height: 1.3;
        }

        .product-desc-clean {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 0.9375rem;
        }

        .product-meta-clean {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-light);
            border: 1px solid #e2e8f0;
        }

        .product-actions-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
        }

        .product-price-large {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .product-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-view-details,
        .btn-add-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            white-space: nowrap;
        }

        .btn-view-details {
            background: #f8fafc;
            color: var(--text-dark);
            border: 2px solid #e2e8f0;
        }

        .btn-view-details:hover {
            background: white;
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn-add-cart {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        /* No Products */
        .no-products-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .no-products-icon {
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1.5rem;
            color: var(--text-light);
            opacity: 0.4;
        }

        .no-products-title {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .no-products-text {
            color: var(--text-light);
            font-size: 1.125rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .product-header-row {
                flex-direction: column;
            }

            .product-actions-right {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .product-buttons {
                flex-direction: row;
            }
        }

        @media (max-width: 768px) {
            .products-page-header {
                padding: 5rem 1.5rem 2rem;
            }

            .container-products {
                padding: 0 1rem;
            }

            .category-filter-section {
                padding: 1.5rem;
            }

            .category-header-clean {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .product-item-clean {
                flex-direction: column;
                gap: 1.5rem;
            }

            .product-image-clean {
                width: 100%;
                height: 200px;
            }

            .product-actions-right {
                width: 100%;
            }

            .product-buttons {
                width: 100%;
            }

            .btn-view-details,
            .btn-add-cart {
                flex: 1;
            }
        }

        @media (max-width: 640px) {
            .category-name-clean {
                font-size: 1.5rem;
            }

            .product-price-large {
                font-size: 1.875rem;
            }

            .btn-view-details span,
            .btn-add-cart span {
                display: none;
            }

            .btn-view-details,
            .btn-add-cart {
                padding: 0.875rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryPills = document.querySelectorAll('.category-pill-modern');
            const categorySections = document.querySelectorAll('.category-section-modern');
            
            // Category filtering
            categoryPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    const selectedCategory = this.getAttribute('data-category');
                    
                    // Update active pill
                    categoryPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter sections
                    categorySections.forEach(section => {
                        const sectionCategory = section.getAttribute('data-category-name');
                        
                        if (selectedCategory === 'all') {
                            section.classList.remove('hidden');
                        } else if (sectionCategory === selectedCategory) {
                            section.classList.remove('hidden');
                        } else {
                            section.classList.add('hidden');
                        }
                    });
                });
            });
            
            // Add to cart functionality
            document.querySelectorAll('.btn-add-cart').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    const originalHTML = this.innerHTML;
                    
                    fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Success state
                            this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><span>Added!</span>';
                            this.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                            
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
                                this.innerHTML = originalHTML;
                                this.style.background = 'linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%)';
                            }, 2000);
                        } else {
                            alert(data.message || 'Failed to add item to cart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add item to cart. Please try again.');
                    });
                });
            });
        });
    </script>
</x-app-layout>
