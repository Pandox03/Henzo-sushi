<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail-grid">
                <!-- Product Image -->
                <div class="product-image-container">
                    @if($product->has_discount && $product->isDiscountValid())
                        <div class="discount-badge">
                            @if($product->discount_type === 'percentage')
                                -{{ $product->discount_value }}% OFF
                            @else
                                -{{ number_format($product->discount_value, 0) }} MAD OFF
                            @endif
                        </div>
                    @endif
                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1544551763-46a013bb2dcc', '1551218808-94e220e084d2', '1565299624946-b28f40c0fe4b', '1571019613454-1cb2f99b2d8b', '1578662996442-48f60103fc96', '1586190848861-99aa4bd1711f'][$product->id % 6]) . '?w=600&h=400&fit=crop&crop=center' }}" 
                         alt="{{ $product->name }}" 
                         class="product-main-image">
                </div>
                
                <!-- Product Info -->
                <div class="product-info-container">
                    <div class="product-category">{{ $product->category->name }}</div>
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <p class="product-description">{{ $product->description }}</p>
                    
                    <!-- Ingredients -->
                    <div class="ingredients-section">
                        <h3 class="ingredients-title">Ingredients</h3>
                        <div class="ingredients-list">
                            @if(str_contains(strtolower($product->name), 'salmon'))
                                <span class="ingredient-tag">Fresh Salmon</span>
                                <span class="ingredient-tag">Sushi Rice</span>
                                <span class="ingredient-tag">Nori</span>
                                <span class="ingredient-tag">Wasabi</span>
                            @elseif(str_contains(strtolower($product->name), 'tuna'))
                                <span class="ingredient-tag">Premium Tuna</span>
                                <span class="ingredient-tag">Sushi Rice</span>
                                <span class="ingredient-tag">Nori</span>
                                <span class="ingredient-tag">Ginger</span>
                            @elseif(str_contains(strtolower($product->name), 'eel'))
                                <span class="ingredient-tag">Grilled Eel</span>
                                <span class="ingredient-tag">Sweet Sauce</span>
                                <span class="ingredient-tag">Sushi Rice</span>
                                <span class="ingredient-tag">Cucumber</span>
                            @elseif(str_contains(strtolower($product->name), 'roll'))
                                <span class="ingredient-tag">Rice</span>
                                <span class="ingredient-tag">Nori</span>
                                <span class="ingredient-tag">Fresh Fish</span>
                                <span class="ingredient-tag">Vegetables</span>
                                <span class="ingredient-tag">Sesame</span>
                            @elseif(str_contains(strtolower($product->name), 'soup'))
                                <span class="ingredient-tag">Miso Paste</span>
                                <span class="ingredient-tag">Tofu</span>
                                <span class="ingredient-tag">Seaweed</span>
                                <span class="ingredient-tag">Green Onions</span>
                            @else
                                <span class="ingredient-tag">Fresh Ingredients</span>
                                <span class="ingredient-tag">Traditional Recipe</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Price and Time -->
                    <div class="price-time-section">
                        <div class="price-container">
                            @if($product->has_discount && $product->isDiscountValid())
                                <div class="product-price">
                                    <span class="text-red-500 line-through text-sm">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-green-600 font-bold text-2xl">${{ number_format($product->discounted_price, 2) }}</span>
                                </div>
                                <span class="price-label">per serving</span>
                            @else
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <span class="price-label">per serving</span>
                            @endif
                        </div>
                        <div class="time-container">
                            <span class="preparation-time">⏱️ {{ $product->preparation_time }} minutes</span>
                        </div>
                    </div>
                    
                    <!-- Add to Cart -->
                    <div class="add-to-cart-section">
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                            <span class="quantity-display" id="quantity">1</span>
                            <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart()">
                            Add to Cart - $<span id="total-price">{{ number_format($product->has_discount && $product->isDiscountValid() ? $product->discounted_price : $product->price, 2) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="related-products-section">
        <div class="container">
            <h2 class="related-title">You might also like</h2>
            <div class="related-products-grid">
                @foreach($relatedProducts as $relatedProduct)
                <div class="related-product-card">
                    <div class="related-product-image">
                        @if($relatedProduct->has_discount && $relatedProduct->isDiscountValid())
                            <div class="discount-badge">
                                @if($relatedProduct->discount_type === 'percentage')
                                    -{{ $relatedProduct->discount_value }}% OFF
                                @else
                                    -{{ number_format($relatedProduct->discount_value, 0) }} MAD OFF
                                @endif
                            </div>
                        @endif
                        <img src="{{ $relatedProduct->image ?: 'https://images.unsplash.com/photo-' . (['1544551763-46a013bb2dcc', '1551218808-94e220e084d2', '1565299624946-b28f40c0fe4b', '1571019613454-1cb2f99b2d8b', '1578662996442-48f60103fc96', '1586190848861-99aa4bd1711f'][$relatedProduct->id % 6]) . '?w=300&h=200&fit=crop&crop=center' }}" 
                             alt="{{ $relatedProduct->name }}" 
                             loading="lazy">
                    </div>
                    <div class="related-product-info">
                        <h4 class="related-product-title">{{ $relatedProduct->name }}</h4>
                        <div class="related-product-price">
                            @if($relatedProduct->has_discount && $relatedProduct->isDiscountValid())
                                <span class="text-red-500 line-through text-xs">${{ number_format($relatedProduct->price, 2) }}</span>
                                <span class="text-green-600 font-bold">${{ number_format($relatedProduct->discounted_price, 2) }}</span>
                            @else
                                <span>${{ number_format($relatedProduct->price, 2) }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $relatedProduct) }}" class="view-related-btn">View Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <style>
        /* Product Detail Section */
        .product-detail-section {
            background: white;
            padding: 3rem 0;
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        .product-image-container {
            position: relative;
        }

        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
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

        .product-main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .product-info-container {
            padding: 1rem 0;
        }

        .product-category {
            color: #d4af37;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .product-description {
            color: #7f8c8d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .ingredients-section {
            margin-bottom: 2rem;
        }

        .ingredients-title {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .ingredients-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .ingredient-tag {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .price-time-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .price-container {
            display: flex;
            flex-direction: column;
        }

        .product-price {
            font-size: 2rem;
            font-weight: 700;
            color: #d4af37;
        }

        .price-label {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .preparation-time {
            color: #7f8c8d;
            font-size: 1rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .add-to-cart-section {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 25px;
            padding: 0.5rem;
        }

        .quantity-btn {
            background: #d4af37;
            color: #2c3e50;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #b8941f;
        }

        .quantity-display {
            margin: 0 1rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            min-width: 30px;
            text-align: center;
        }

        .add-to-cart-btn {
            background: #d4af37;
            color: #2c3e50;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .add-to-cart-btn:hover {
            background: #b8941f;
            transform: translateY(-2px);
        }

        /* Related Products */
        .related-products-section {
            background: #f8f9fa;
            padding: 3rem 0;
        }

        .related-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
        }

        .related-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .related-product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .related-product-image {
            height: 150px;
            overflow: hidden;
        }

        .related-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-product-info {
            padding: 1rem;
        }

        .related-product-title {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .related-product-image {
            position: relative;
        }

        .related-product-price {
            color: #d4af37;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .related-product-price .line-through {
            color: #95a5a6;
            font-size: 0.85rem;
            text-decoration: line-through;
        }

        .related-product-price .font-bold {
            color: #27ae60;
            font-size: 1.1rem;
        }

        .view-related-btn {
            color: #d4af37;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .view-related-btn:hover {
            color: #b8941f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .product-title {
                font-size: 2rem;
            }
            
            .price-time-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .add-to-cart-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .quantity-selector {
                justify-content: center;
            }
        }
    </style>

    <script>
        let quantity = 1;
        const basePrice = {{ $product->has_discount && $product->isDiscountValid() ? $product->discounted_price : $product->price }};

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
            const productId = {{ $product->id }};
            const btn = document.querySelector('.add-to-cart-btn');
            
            // Show success message
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Added to Cart!';
            btn.style.background = '#28a745';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = '#d4af37';
            }, 2000);
            
            console.log('Added to cart:', {
                productId: productId,
                quantity: quantity,
                totalPrice: basePrice * quantity
            });
        }
    </script>
</x-app-layout>

