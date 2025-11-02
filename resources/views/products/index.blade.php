<x-app-layout>
    <!-- Sticky Category Navigation -->
    <div class="sticky-nav-container">
        <div class="sticky-category-nav" id="sticky-nav">
            <div class="container">
                <div class="category-nav">
                    <button class="category-btn active" data-category="all">
                        All
                    </button>
                    @foreach($categories as $category)
                    <button class="category-btn" data-category="{{ $category->name }}">
                        @if($category->name === 'Nigiri') 🍣
                        @elseif($category->name === 'Maki Rolls') 🍱
                        @elseif($category->name === 'Sashimi') 🐟
                        @elseif($category->name === 'Appetizers') 🥢
                        @else 🍽️
                        @endif
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            @if($products->count() > 0)
                <!-- Products by Category - Vertical Layout -->
                @foreach($categories as $category)
                <div class="category-section" id="category-{{ Str::slug($category->name) }}" data-category="{{ $category->name }}">
                    <div class="category-header">
                        <h3 class="category-title">
                            @if($category->name === 'Nigiri') 🍣
                            @elseif($category->name === 'Maki Rolls') 🍱
                            @elseif($category->name === 'Sashimi') 🐟
                            @elseif($category->name === 'Appetizers') 🥢
                            @else 🍽️
                            @endif
                            {{ $category->name }}
                        </h3>
                        <p class="category-description">
                            @if($category->name === 'Nigiri')
                                Traditional sushi with fish on rice
                            @elseif($category->name === 'Maki Rolls')
                                Rolled sushi with seaweed
                            @elseif($category->name === 'Sashimi')
                                Fresh raw fish slices
                            @elseif($category->name === 'Appetizers')
                                Japanese appetizers and sides
                            @else
                                Delicious {{ $category->name }} dishes
                            @endif
                        </p>
                    </div>
                    
                    <div class="products-grid">
                        @foreach($category->products->take(8) as $product)
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->has_discount && $product->isDiscountValid())
                                    <div class="discount-badge">
                                        @if($product->discount_type === 'percentage')
                                            -{{ $product->discount_value }}% OFF
                                        @else
                                            -{{ number_format($product->discount_value, 0) }} MAD OFF
                                        @endif
                                    </div>
                                @endif
                                <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1544551763-46a013bb2dcc', '1551218808-94e220e084d2', '1565299624946-b28f40c0fe4b', '1571019613454-1cb2f99b2d8b', '1578662996442-48f60103fc96', '1586190848861-99aa4bd1711f'][$loop->index % 6]) . '?w=300&h=200&fit=crop&crop=center' }}" 
                                     alt="{{ $product->name }}" 
                                     loading="lazy">
                                <div class="product-overlay">
                                    <a href="{{ route('products.show', $product) }}" class="view-product-btn">
                                        View Details
                                    </a>
                                    <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                            <div class="product-info">
                                <h4 class="product-title">{{ $product->name }}</h4>
                                <p class="product-description">{{ Str::limit($product->description, 60) }}</p>
                                <div class="product-ingredients">
                                    <small class="ingredients-label">Ingredients:</small>
                                    <span class="ingredients-text">
                                        @if(str_contains(strtolower($product->name), 'salmon'))
                                            Fresh Salmon, Sushi Rice, Nori, Wasabi
                                        @elseif(str_contains(strtolower($product->name), 'tuna'))
                                            Premium Tuna, Sushi Rice, Nori, Ginger
                                        @elseif(str_contains(strtolower($product->name), 'eel'))
                                            Grilled Eel, Sweet Sauce, Sushi Rice, Cucumber
                                        @elseif(str_contains(strtolower($product->name), 'roll'))
                                            Rice, Nori, Fresh Fish, Vegetables, Sesame
                                        @elseif(str_contains(strtolower($product->name), 'soup'))
                                            Miso Paste, Tofu, Seaweed, Green Onions
                                        @else
                                            Fresh Ingredients, Traditional Recipe
                                        @endif
                                    </span>
                                </div>
                                <div class="product-footer">
                                    <div class="price-time">
                                        <div class="product-price">
                                            @if($product->has_discount && $product->isDiscountValid())
                                                <span class="text-red-500 line-through text-sm">${{ number_format($product->price, 2) }}</span>
                                                <span class="text-green-600 font-bold">${{ number_format($product->discounted_price, 2) }}</span>
                                            @else
                                                <span>${{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <span class="product-time">⏱️ {{ $product->preparation_time }}min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                
                <!-- Pagination -->
                <div class="pagination-container">
                    {{ $products->links() }}
                </div>
            @else
                <div class="no-products">
                    <div class="no-products-icon">🍣</div>
                    <h3>No products found</h3>
                    <p>Try adjusting your filter or check back later for new items.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary">View All Products</a>
                </div>
            @endif
        </div>
    </section>

    <style>
        /* Sticky Category Navigation */
        .sticky-nav-container {
            position: relative;
            z-index: 1000;
            background: white;
            margin-top: 0;
        }

        .sticky-category-nav {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 0;
            transition: all 0.3s ease;
            position: relative;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 1000;
        }

        .sticky-category-nav.sticky {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000 !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Ensure category nav is always above other elements */
        .sticky-nav-container {
            position: relative;
            z-index: 1000 !important;
        }

        .sticky-category-nav {
            position: relative !important;
            z-index: 1000 !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Force visibility when not sticky */
        .sticky-category-nav:not(.sticky) {
            position: relative !important;
            z-index: 1000 !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .category-nav {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding: 0.5rem 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
            justify-content: center;
        }

        .category-nav::-webkit-scrollbar {
            display: none;
        }

        .category-btn {
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #7f8c8d;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .category-btn:hover {
            background: #e9ecef;
            color: #2c3e50;
        }

        .category-btn.active {
            background: #d4af37;
            color: #2c3e50;
            border-color: #d4af37;
        }

        /* Products Section */
        .products-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }

        .category-section {
            margin-bottom: 4rem;
            padding: 2rem 0;
        }

        .category-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .category-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .category-description {
            color: #7f8c8d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .view-product-btn, .add-to-cart-btn {
            background: #d4af37;
            color: #2c3e50;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .view-product-btn:hover, .add-to-cart-btn:hover {
            background: #b8941f;
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
            line-height: 1.3;
        }

        .product-description {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .product-ingredients {
            margin-bottom: 1rem;
        }

        .ingredients-label {
            color: #7f8c8d;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ingredients-text {
            color: #2c3e50;
            font-size: 0.85rem;
            line-height: 1.3;
            display: block;
            margin-top: 0.25rem;
        }

        .product-footer {
            border-top: 1px solid #f0f0f0;
            padding-top: 1rem;
        }

        .price-time {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            color: #d4af37;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .product-price .line-through {
            color: #95a5a6;
            font-size: 0.9rem;
            text-decoration: line-through;
        }

        .product-price .font-bold {
            color: #27ae60;
            font-size: 1.3rem;
        }

        .product-time {
            color: #7f8c8d;
            font-size: 0.85rem;
            background: #f8f9fa;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
        }

        /* No Products */
        .no-products {
            text-align: center;
            padding: 4rem 2rem;
        }

        .no-products-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .no-products h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .no-products p {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .category-nav {
                justify-content: flex-start;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryBtns = document.querySelectorAll('.category-btn');
            const categorySections = document.querySelectorAll('.category-section');
            const stickyNav = document.getElementById('sticky-nav');
            const productsSection = document.getElementById('products');
            
            // Sticky navigation functionality
            function handleStickyNav() {
                const productsSectionTop = productsSection.offsetTop;
                const scrollTop = window.pageYOffset;
                
                console.log('Scroll Top:', scrollTop, 'Products Section Top:', productsSectionTop);
                
                if (scrollTop >= productsSectionTop - 50) {
                    console.log('Adding sticky class');
                    stickyNav.classList.add('sticky');
                    // Clear inline styles when sticky
                    stickyNav.style.position = '';
                    stickyNav.style.zIndex = '';
                    stickyNav.style.display = '';
                    stickyNav.style.opacity = '';
                    stickyNav.style.visibility = '';
                } else {
                    console.log('Removing sticky class');
                    stickyNav.classList.remove('sticky');
                    // Ensure the nav is visible when not sticky
                    stickyNav.style.position = 'relative';
                    stickyNav.style.zIndex = '1000';
                    stickyNav.style.display = 'block';
                    stickyNav.style.opacity = '1';
                    stickyNav.style.visibility = 'visible';
                }
            }
            
            // Category button click functionality
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const selectedCategory = this.getAttribute('data-category');
                    
                    // Update active button
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (selectedCategory === 'all') {
                        // Show all sections
                        categorySections.forEach(section => {
                            section.style.display = 'block';
                        });
                        // Scroll to products section
                        productsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        // Show only selected category
                        categorySections.forEach(section => {
                            const sectionCategory = section.getAttribute('data-category');
                            if (sectionCategory === selectedCategory) {
                                section.style.display = 'block';
                                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    }
                });
            });
            
            // Intersection Observer for active category highlighting
            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -70% 0px',
                threshold: 0
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const categoryName = entry.target.getAttribute('data-category');
                        categoryBtns.forEach(btn => {
                            btn.classList.remove('active');
                            if (btn.getAttribute('data-category') === categoryName) {
                                btn.classList.add('active');
                            }
                        });
                    }
                });
            }, observerOptions);
            
            // Observe all category sections
            categorySections.forEach(section => {
                observer.observe(section);
            });
            
            // Scroll event listener for sticky nav
            window.addEventListener('scroll', handleStickyNav);
            
            // Ensure nav is properly positioned on page load
            handleStickyNav();
            
            // Add to cart functionality
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    
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
                            console.log('Cart response:', data); // Debug log
                            
                            if (data.success) {
                                // Show success message
                                this.textContent = 'Added!';
                                this.style.background = '#28a745';
                                
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
                                    this.textContent = 'Add to Cart';
                                    this.style.background = '#d4af37';
                                }, 2000);
                            } else {
                                alert(data.message || 'Failed to add item to cart');
                            }
                        })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add item to cart');
                    });
                });
            });
        });

    </script>
</x-app-layout>
