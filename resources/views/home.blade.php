<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to Henzo Sushi') }}
        </h2>
    </x-slot>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                Welcome to <span class="text-yellow-400">Henzo Sushi</span>
            </h1>
            <p class="hero-description">
                Experience the finest Japanese cuisine with authentic flavors, fresh ingredients, and traditional techniques. 
                Every dish tells a story of culinary excellence.
            </p>
            <div class="hero-buttons">
                <a href="#menu" class="btn-primary">
                    🍣 Explore Menu
                </a>
                <a href="#order" class="btn-outline">
                    📱 Order Now
                </a>
            </div>
        </div>
        
        <!-- Floating Sushi Animation -->
        <div class="floating-sushi">
            <div class="sushi-icon">🍣</div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Story</h2>
                <p class="section-description">
                    For over a decade, we've been crafting exceptional sushi experiences that celebrate the art of Japanese cuisine.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🍱</div>
                    <h3 class="feature-title">Fresh Ingredients</h3>
                    <p class="feature-description">
                        We source the finest fish and ingredients daily, ensuring every piece of sushi meets our high standards of quality and freshness.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👨‍🍳</div>
                    <h3 class="feature-title">Master Chefs</h3>
                    <p class="feature-description">
                        Our skilled chefs bring years of experience and traditional techniques to create authentic Japanese flavors.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3 class="feature-title">Fast Delivery</h3>
                    <p class="feature-description">
                        Enjoy our delicious sushi at home with our fast and reliable delivery service, maintaining quality from kitchen to your door.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Sticky Category Navigation -->
    <div class="sticky-nav-container">
        <div class="sticky-category-nav" id="sticky-nav">
            <div class="container">
                <div class="category-nav">
                    <button class="category-btn active" data-category="all">All</button>
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

    <!-- Menu Section -->
    <section class="menu-section" id="menu">
        <div class="container">
            
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
                    <p class="category-description">{{ $category->description }}</p>
                </div>
                
                <div class="products-grid">
                    @foreach($category->products->take(8) as $product)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1544551763-46a013bb2dcc', '1551218808-94e220e084d2', '1565299624946-b28f40c0fe4b', '1571019613454-1cb2f99b2d8b', '1578662996442-48f60103fc96', '1586190848861-99aa4bd1711f'][$loop->index % 6]) . '?w=300&h=200&fit=crop&crop=center' }}" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy">
                            <div class="product-overlay">
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
                                    <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                    <span class="product-time">⏱️ {{ $product->preparation_time }}min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($category->products->count() > 8)
                <div class="view-more-category">
                    <a href="{{ route('products.index', ['category' => $category->name]) }}" class="btn-outline">
                        View All {{ $category->name }} 🍣
                    </a>
                </div>
                @endif
            </div>
            @endforeach
            
            <div class="view-more-section">
                <a href="{{ route('products.index') }}" class="btn-primary view-more-btn">
                    View All Products 🍣
                </a>
            </div>
        </div>
    </section>

    <!-- Order Online Section -->
    <section id="order" class="order-section">
        <div class="container">
            <div class="order-content">
                <h2 class="order-title">Ready to Order?</h2>
                <p class="order-description">
                    Skip the wait and order online! Get your favorite sushi delivered fresh to your door.
                </p>
                <div class="order-buttons">
                    @guest
                        <a href="{{ route('register') }}" class="btn-primary">🍣 Start Ordering</a>
                        <a href="{{ route('login') }}" class="btn-outline">👤 Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-primary">🍣 Order Now</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get in Touch</h2>
                <p class="section-description">
                    We'd love to hear from you! Contact us for reservations, questions, or just to say hello.
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3 class="contact-title">Location</h3>
                    <p class="contact-info">123 Sushi Street<br>Tokyo District, City 12345</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <h3 class="contact-title">Phone</h3>
                    <p class="contact-info">+1 (555) 123-SUSHI<br>Mon-Sun: 11:00 AM - 10:00 PM</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <h3 class="contact-title">Email</h3>
                    <p class="contact-info">info@henzosushi.com<br>orders@henzosushi.com</p>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), 
                        linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-description {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: #d4af37;
            color: #2c3e50;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            background: #b8941f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-outline:hover {
            background: white;
            color: #2c3e50;
        }

        .floating-sushi {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            animation: float 3s ease-in-out infinite;
        }

        .sushi-icon {
            font-size: 2rem;
        }

        @keyframes float {
            0%, 100% { transform: translateX(-50%) translateY(0px); }
            50% { transform: translateX(-50%) translateY(-10px); }
        }

        /* About Section */
        .about-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .section-description {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            text-align: center;
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .feature-description {
            color: #7f8c8d;
            line-height: 1.6;
        }

        /* Menu Section */
        .menu-section {
            padding: 4rem 0;
        }


        /* Sticky Category Navigation */
        .sticky-nav-container {
            position: relative;
            z-index: 100;
        }

        .sticky-category-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .sticky-category-nav.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
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

        /* Menu Section */
        .menu-section {
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

        .view-more-category {
            text-align: center;
            margin-top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            min-width: 280px;
            max-width: 320px;
            flex-shrink: 0;
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .add-to-cart-btn {
            background: #d4af37;
            color: #2c3e50;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
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
        }

        .product-time {
            color: #7f8c8d;
            font-size: 0.85rem;
            background: #f8f9fa;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
        }

        .view-more-section {
            text-align: center;
            margin-top: 3rem;
        }

        .view-more-btn {
            font-size: 1.1rem;
            padding: 1rem 2rem;
        }

        /* Order Section */
        .order-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .order-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .order-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .order-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .order-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Contact Section */
        .contact-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .contact-card {
            text-align: center;
            padding: 30px;
        }

        .contact-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .contact-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .contact-info {
            color: #7f8c8d;
        }

        /* Responsive Design */
        
        /* Large Desktop (1200px and up) */
        @media (min-width: 1200px) {
            .container {
                max-width: 1400px;
            }
            
            .hero-title {
                font-size: 5rem;
            }
            
            .section-title {
                font-size: 3.5rem;
            }
        }
        
        /* Desktop (992px to 1199px) */
        @media (max-width: 1199px) {
            .hero-title {
                font-size: 3.5rem;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
        }
        
        /* Tablet (768px to 991px) */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-description {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
            }
            
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 25px;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
            }
        }
        
        /* Mobile Large (576px to 767px) */
        @media (max-width: 767px) {
            .hero-section {
                height: 80vh;
                padding: 2rem 0;
            }
            
            .hero-title {
                font-size: 2.5rem;
                line-height: 1.1;
            }
            
            .hero-description {
                font-size: 1.1rem;
                margin-bottom: 1.5rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .btn-primary, .btn-outline {
                width: 100%;
                max-width: 280px;
                text-align: center;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .section-description {
                font-size: 1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .categories-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .category-card, .product-card {
                padding: 20px;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .order-title {
                font-size: 2.2rem;
            }
            
            .order-buttons {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }
        
        /* Mobile Small (320px to 575px) */
        @media (max-width: 575px) {
            .container {
                padding: 0 15px;
            }
            
            .hero-section {
                height: 70vh;
                padding: 1.5rem 0;
            }
            
            .hero-title {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            
            .hero-description {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .section-description {
                font-size: 0.95rem;
            }
            
            .feature-icon, .category-icon, .product-icon, .contact-icon {
                font-size: 2.5rem;
            }
            
            .feature-title, .category-title, .contact-title {
                font-size: 1.3rem;
            }
            
            .product-title {
                font-size: 1.1rem;
            }
            
            .category-card, .product-card, .contact-card {
                padding: 15px;
            }
            
            .order-title {
                font-size: 1.8rem;
            }
            
            .order-description {
                font-size: 1rem;
            }
            
            .floating-sushi {
                bottom: 10px;
            }
            
            .sushi-icon {
                font-size: 1.5rem;
            }
        }
        
        /* Extra Small Mobile (up to 320px) */
        @media (max-width: 320px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
            
            .btn-primary, .btn-outline {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }
        
        /* Landscape Mobile */
        @media (max-height: 500px) and (orientation: landscape) {
            .hero-section {
                height: 100vh;
                padding: 1rem 0;
            }
            
            .hero-title {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            
            .hero-description {
                font-size: 1rem;
                margin-bottom: 1rem;
            }
            
            .hero-buttons {
                flex-direction: row;
                gap: 15px;
            }
        }
        
        /* High DPI Displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .hero-section {
                background-attachment: scroll;
            }
        }
        
        /* Print Styles */
        @media print {
            .hero-section, .order-section {
                background: white !important;
                color: black !important;
            }
            
            .btn-primary, .btn-outline {
                border: 1px solid black !important;
                background: white !important;
                color: black !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryBtns = document.querySelectorAll('.category-btn');
            const categorySections = document.querySelectorAll('.category-section');
            const stickyNav = document.getElementById('sticky-nav');
            const menuSection = document.getElementById('menu');
            
            // Sticky navigation functionality
            function handleStickyNav() {
                const menuSectionTop = menuSection.offsetTop;
                const scrollTop = window.pageYOffset;
                
                if (scrollTop >= menuSectionTop - 100) {
                    stickyNav.classList.add('sticky');
                } else {
                    stickyNav.classList.remove('sticky');
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
                        // Scroll to menu section
                        menuSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        // Find and scroll to selected category
                        const targetSection = document.getElementById('category-' + selectedCategory.toLowerCase().replace(/\s+/g, '-'));
                        if (targetSection) {
                            targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
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
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
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