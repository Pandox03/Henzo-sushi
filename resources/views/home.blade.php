<x-app-layout>
    <!-- Hero Section - Eye-Catching Design -->
    <section class="hero-section-modern">
        <!-- Background Image with Overlay -->
        <div class="hero-bg-wrapper">
            <div class="hero-bg-image"></div>
            <div class="hero-gradient-overlay"></div>
        </div>
        
        <!-- Animated Particles -->
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="hero-content-wrapper">
            <div class="hero-badge-modern">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>Authentic Japanese Cuisine</span>
            </div>
            
            <h1 class="hero-title-modern">
                Experience the Art of
                <span class="hero-highlight">Sushi</span>
            </h1>
            
            <p class="hero-subtitle-modern">
                Crafted with precision, served with passion. Discover traditional Japanese flavors 
                reimagined with modern elegance. Fresh ingredients, master chefs, unforgettable taste.
            </p>
            
            <div class="hero-cta-container">
                <a href="#menu" class="hero-btn-primary">
                    <span>Explore Menu</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="hero-btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Start Ordering</span>
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="hero-btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Order Now</span>
                    </a>
                @endguest
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="hero-scroll-indicator">
            <span>Scroll to explore</span>
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Why Choose Us</span>
                <h2 class="section-title">Tradition Meets Excellence</h2>
                <p class="section-description">
                    For over a decade, we've been crafting exceptional sushi experiences that celebrate 
                    the art of Japanese cuisine with unwavering dedication to quality.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-description">
                        We source the finest fish and ingredients daily, ensuring every piece of sushi 
                        meets our high standards of quality and freshness.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Expert Chefs</h3>
                    <p class="feature-description">
                        Our master chefs bring years of experience and traditional techniques to create 
                        authentic Japanese flavors with every dish.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Swift Delivery</h3>
                    <p class="feature-description">
                        Enjoy our delicious sushi at home with our fast and reliable delivery service, 
                        maintaining quality from kitchen to your door.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Menu Section - Modern Clean Design -->
    <section class="menu-section" id="menu">
        <div class="container">
            <div class="menu-header">
                <span class="section-label">Our Menu</span>
                <h2 class="menu-title">Discover Our Signature Dishes</h2>
                <p class="menu-description">Handcrafted sushi and Japanese delicacies made with the finest ingredients</p>
            </div>

            <!-- Category Filter Pills -->
            <div class="category-filter">
                <button class="filter-pill active" data-category="all">
                    All Dishes
                </button>
                @foreach($categories as $category)
                <button class="filter-pill" data-category="{{ $category->slug ?? Str::slug($category->name) }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
            
            <!-- Products Grid - Clean & Unified -->
            @foreach($categories as $categoryIndex => $category)
            <div class="category-block" data-category-block="{{ $category->slug ?? Str::slug($category->name) }}">
                <div class="category-divider">
                    <span class="category-name">{{ $category->name }}</span>
                </div>
                
                <div class="products-modern-grid">
                    @foreach($category->products->take(6) as $product)
                    <div class="product-modern-card">
                        <div class="product-image-wrapper">
                            @if($loop->first)
                                <span class="product-badge-new">Popular</span>
                            @endif
                            @if($product->has_discount && $product->isDiscountValid())
                                <div class="discount-badge">
                                    @if($product->discount_type === 'percentage')
                                        -{{ $product->discount_value }}% OFF
                                    @else
                                        -{{ number_format($product->discount_value, 0) }} MAD OFF
                                    @endif
                                </div>
                            @endif
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-' . (['1579584425555-c3ce17fd4351', '1583623025817-d180a2221d0a', '1564489563601-c53e96a14d2f', '1574071318508-1cdbab80d002', '1579027989536-46b295e8c8c1', '1582878826629-29b7ad1cdc43'][$loop->index % 6]) . '?w=600&h=400&fit=crop' }}" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy">
                            <div class="product-quick-add">
                                <button class="quick-add-btn" data-product-id="{{ $product->id }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-top">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <span class="product-price-tag">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <p class="product-desc">{{ Str::limit($product->description, 100) }}</p>
                            <div class="product-footer">
                                <div class="product-tags">
                                    <span class="tag">{{ $product->preparation_time ?? '15' }} min</span>
                                    <span class="tag">Fresh</span>
                                </div>
                                <div class="product-price">
                                    @if($product->has_discount && $product->isDiscountValid())
                                        <span class="line-through text-sm" style="color: #95a5a6;">${{ number_format($product->price, 2) }}</span>
                                        <span class="font-bold" style="color: #27ae60;">${{ number_format($product->discounted_price, 2) }}</span>
                                    @else
                                        <span>${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($category->products->count() > 6)
                <div class="see-more-wrapper">
                    <a href="{{ route('products.index', ['category' => $category->name]) }}" class="see-more-link">
                        See all {{ $category->name }} 
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
            @endforeach
            
            <div class="menu-footer-cta">
                <a href="{{ route('products.index') }}" class="btn-primary-large">
                    Browse Full Menu
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
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
                    Skip the wait and order online. Get your favorite sushi delivered fresh to your door 
                    with our seamless ordering experience.
                </p>
                <div class="order-buttons">
                    @guest
                        <a href="{{ route('register') }}" class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Start Ordering
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Login
                        </a>
                    @else
                        <a href="{{ route('products.index') }}" class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Order Now
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Contact Us</span>
                <h2 class="section-title">Get in Touch</h2>
                <p class="section-description">
                    We'd love to hear from you. Reach out for reservations, inquiries, or feedback.
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="contact-title">Location</h3>
                    <p class="contact-info">123 Sushi Street<br>Tokyo District, City 12345</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="contact-title">Phone</h3>
                    <p class="contact-info">+1 (555) 123-SUSHI<br>Mon-Sun: 11:00 AM - 10:00 PM</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="contact-title">Email</h3>
                    <p class="contact-info">info@henzosushi.com<br>orders@henzosushi.com</p>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Modern Clean Styles */
        :root {
            --primary: #d4af37;
            --primary-dark: #b8941f;
            --secondary: #2c3e50;
            --accent: #d4af37;
            --text-dark: #1a202c;
            --text-light: #64748b;
            --bg-light: #ffffff;
            --border-light: #e2e8f0;
        }

        /* Hero Section - Modern Eye-Catching Design */
        .hero-section-modern {
            min-height: 100vh;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 8rem 2rem 3rem;
            margin-top: 0;
        }

        /* Background */
        .hero-bg-wrapper {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        .hero-bg-image {
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?q=80&w=2400&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: slowZoom 20s ease-in-out infinite alternate;
        }

        @keyframes slowZoom {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }

        .hero-gradient-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.95) 0%, 
                rgba(30, 41, 59, 0.90) 40%,
                rgba(51, 65, 85, 0.88) 70%,
                rgba(30, 41, 59, 0.92) 100%);
            backdrop-filter: blur(2px);
        }

        /* Animated Particles */
        .hero-particles {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 8s ease-in-out infinite;
            box-shadow: 0 0 20px var(--primary);
        }

        .particle:nth-child(1) {
            left: 20%;
            animation-delay: 0s;
            animation-duration: 10s;
        }

        .particle:nth-child(2) {
            left: 40%;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .particle:nth-child(3) {
            left: 60%;
            animation-delay: 4s;
            animation-duration: 9s;
        }

        .particle:nth-child(4) {
            left: 80%;
            animation-delay: 1s;
            animation-duration: 11s;
        }

        .particle:nth-child(5) {
            left: 50%;
            animation-delay: 3s;
            animation-duration: 13s;
        }

        @keyframes particleFloat {
            0% {
                bottom: -10%;
                opacity: 0;
                transform: translateX(0) scale(0.5);
            }
            25% {
                opacity: 0.6;
                transform: translateX(20px) scale(1);
            }
            50% {
                opacity: 0.8;
                transform: translateX(-20px) scale(1.2);
            }
            75% {
                opacity: 0.6;
                transform: translateX(10px) scale(1);
            }
            100% {
                top: -10%;
                opacity: 0;
                transform: translateX(0) scale(0.5);
            }
        }

        /* Content Wrapper */
        .hero-content-wrapper {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 10;
            color: white;
            animation: fadeInUp 1s ease-out;
            padding: 0 1rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Badge */
        .hero-badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(244, 208, 63, 0.15) 100%);
            backdrop-filter: blur(20px);
            padding: 0.625rem 1.5rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(212, 175, 55, 0.4);
            box-shadow: 0 10px 40px rgba(212, 175, 55, 0.3);
            color: var(--primary);
            animation: badgePulse 3s ease-in-out infinite;
        }

        @keyframes badgePulse {
            0%, 100% { 
                box-shadow: 0 10px 40px rgba(212, 175, 55, 0.3);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 15px 50px rgba(212, 175, 55, 0.5);
                transform: scale(1.02);
            }
        }

        /* Title */
        .hero-title-modern {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 1.25rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: #ffffff;
            text-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from {
                text-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
            }
            to {
                text-shadow: 0 4px 40px rgba(212, 175, 55, 0.4), 0 8px 50px rgba(0, 0, 0, 0.6);
            }
        }

        .hero-highlight {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            position: relative;
            display: inline-block;
        }

        /* Subtitle */
        .hero-subtitle-modern {
            font-size: clamp(1rem, 2vw, 1.125rem);
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            color: #e2e8f0;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.5);
            font-weight: 400;
        }

        /* CTA Buttons - Highly Visible */
        .hero-cta-container {
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            position: relative;
            z-index: 100;
        }

        .hero-btn-primary,
        .hero-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 100;
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            box-shadow: 0 15px 40px -5px rgba(212, 175, 55, 0.6), 
                        0 0 0 0 rgba(212, 175, 55, 0.4);
            animation: buttonPulse 2s ease-in-out infinite;
        }

        @keyframes buttonPulse {
            0%, 100% {
                box-shadow: 0 15px 40px -5px rgba(212, 175, 55, 0.6), 
                            0 0 0 0 rgba(212, 175, 55, 0.4);
            }
            50% {
                box-shadow: 0 20px 50px -5px rgba(212, 175, 55, 0.8), 
                            0 0 0 10px rgba(212, 175, 55, 0);
            }
        }

        .hero-btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .hero-btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .hero-btn-primary:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 25px 60px -5px rgba(212, 175, 55, 0.8);
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.7);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.2);
        }

        .hero-btn-primary span,
        .hero-btn-secondary span {
            position: relative;
            z-index: 2;
        }

        /* Scroll Indicator */
        .hero-scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            z-index: 10;
            cursor: pointer;
        }

        .hero-scroll-indicator:hover {
            color: white;
        }

        /* Common Button Styles (for other sections) */
        .btn-primary, .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            box-shadow: 0 10px 30px -5px rgba(212, 175, 55, 0.5);
            font-weight: 700;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #d4af37 100%);
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -5px rgba(212, 175, 55, 0.6);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
        }

        /* About Section - Dark Theme */
        .about-section {
            background: #0f172a;
            padding: 6rem 2rem;
        }
        
        .about-section .section-label {
            color: var(--primary);
        }
        
        .about-section .section-title,
        .about-section .section-description {
            color: #e2e8f0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-label {
            display: inline-block;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .section-description {
            font-size: clamp(1rem, 2vw, 1.125rem);
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem 1.5rem;
            background: rgba(30, 41, 59, 0.5);
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.7);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 12px;
            color: var(--primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .feature-icon svg {
            width: 32px;
            height: 32px;
        }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: #f1f5f9;
            font-weight: 600;
        }

        .feature-description {
            color: #94a3b8;
            line-height: 1.7;
            font-size: 0.9375rem;
        }

        /* Menu Section - Modern Clean Design */
        .menu-section {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
            position: relative;
        }

        .menu-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .menu-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .menu-description {
            font-size: 1.125rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Category Filter Pills - Clean & Modern */
        .category-filter {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
            padding: 1rem;
        }

        .filter-pill {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            background: white;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .filter-pill:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .filter-pill.active {
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        /* Category Block */
        .category-block {
            margin-bottom: 4rem;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .category-divider {
            position: relative;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .category-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        }

        .category-name {
            position: relative;
            display: inline-block;
            padding: 0.5rem 2rem;
            background: white;
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Products Modern Grid */
        .products-modern-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .product-modern-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
        }

        .product-modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: var(--primary);
        }

        .product-image-wrapper {
            position: relative;
            height: 260px;
            overflow: hidden;
            background: #f8fafc;
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-modern-card:hover .product-image-wrapper img {
            transform: scale(1.05);
        }

        .product-badge-new {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: #0f172a;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
            z-index: 10;
        }

        .product-quick-add {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .product-modern-card:hover .product-quick-add {
            opacity: 1;
            transform: translateY(0);
        }

        .quick-add-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            color: var(--primary);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .quick-add-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1) rotate(90deg);
        }

        .product-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .product-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.3;
            flex: 1;
        }

        .product-price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            white-space: nowrap;
        }

        .product-desc {
            color: var(--text-light);
            font-size: 0.875rem;
            line-height: 1.6;
            flex: 1;
        }

        .product-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .product-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            background: #f8fafc;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-light);
        }

        /* See More Link */
        .see-more-wrapper {
            text-align: center;
            margin-top: 2rem;
        }

        .see-more-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
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

        .see-more-link:hover {
            gap: 0.75rem;
            color: var(--primary-dark);
        }

        /* Menu Footer CTA */
        .menu-footer-cta {
            text-align: center;
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 2px solid #f1f5f9;
        }

        .btn-primary-large {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 3rem;
            background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
            color: #0f172a;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.125rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.3);
        }

        .btn-primary-large:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(212, 175, 55, 0.4);
        }

        /* Order Section - Clean CTA */
        .order-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .order-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .order-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .order-description {
            font-size: clamp(1rem, 2vw, 1.125rem);
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.7;
            color: #cbd5e1;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .order-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Contact Section - Dark Theme */
        .contact-section {
            background: #0f172a;
            padding: 6rem 2rem;
        }
        
        .contact-section .section-label {
            color: var(--primary);
        }
        
        .contact-section .section-title,
        .contact-section .section-description {
            color: #e2e8f0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .contact-card {
            text-align: center;
            padding: 2rem 1.5rem;
            background: rgba(30, 41, 59, 0.5);
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .contact-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.7);
        }

        .contact-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 12px;
            color: var(--primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .contact-icon svg {
            width: 32px;
            height: 32px;
        }

        .contact-title {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            color: #f1f5f9;
            font-weight: 600;
        }

        .contact-info {
            color: #94a3b8;
            line-height: 1.7;
            font-size: 0.9375rem;
        }

        /* Responsive Design - Mobile First */
        @media (max-width: 1024px) {
            .container {
                padding: 0 1.5rem;
            }

            .features-grid,
            .contact-grid {
                gap: 2rem;
            }

            .products-modern-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 1024px) {
            .hero-section-modern {
                padding: 7rem 2rem 3rem;
            }

            .hero-content-wrapper {
                max-width: 700px;
            }
        }

        @media (max-width: 768px) {
            .hero-section-modern {
                min-height: 100vh;
                padding: 6rem 1.5rem 3rem;
            }

            .hero-content-wrapper {
                max-width: 100%;
                padding: 0 0.5rem;
            }

            .hero-badge-modern {
                font-size: 0.75rem;
                padding: 0.5rem 1rem;
                margin-bottom: 1.25rem;
            }

            .hero-title-modern {
                font-size: clamp(1.75rem, 7vw, 2.5rem);
                margin-bottom: 1rem;
            }

            .hero-subtitle-modern {
                font-size: 0.9375rem;
                margin-bottom: 1.75rem;
                line-height: 1.5;
            }

            .hero-btn-primary,
            .hero-btn-secondary {
                padding: 0.875rem 1.75rem;
                font-size: 0.9375rem;
            }

            .hero-scroll-indicator {
                bottom: 1.5rem;
                font-size: 0.8125rem;
            }

            .about-section,
            .menu-section,
            .contact-section,
            .order-section {
                padding: 4rem 1.5rem;
            }

            .section-header {
                margin-bottom: 3rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .category-filter {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 0.5rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
            
            .category-filter::-webkit-scrollbar {
                height: 4px;
            }
            
            .category-filter::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }
            
            .category-filter::-webkit-scrollbar-thumb {
                background: var(--primary);
                border-radius: 10px;
            }

            .products-modern-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1.5rem;
            }

            .product-image-wrapper {
                height: 220px;
            }

            .product-content {
                padding: 1.25rem;
            }

            .product-name {
                font-size: 1rem;
            }

            .product-price-tag {
                font-size: 1.25rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .category-name {
                font-size: 1.5rem;
                padding: 0.5rem 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .hero-section-modern {
                min-height: 100vh;
                padding: 5rem 1rem 2rem;
            }

            .hero-cta-container,
            .order-buttons {
                flex-direction: column;
                width: 100%;
                gap: 0.875rem;
                margin-bottom: 1rem;
            }

            .hero-btn-primary,
            .hero-btn-secondary,
            .btn-primary,
            .btn-outline {
                width: 100%;
                max-width: 350px;
                justify-content: center;
                padding: 0.875rem 1.5rem;
                font-size: 0.9375rem;
            }

            .hero-scroll-indicator {
                display: none;
            }

            .products-modern-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .product-modern-card {
                max-width: 100%;
            }

            .product-image-wrapper {
                height: 240px;
            }

            .filter-pill {
                font-size: 0.875rem;
                padding: 0.625rem 1.25rem;
            }

            .quick-add-btn {
                width: 44px;
                height: 44px;
            }

            .btn-primary-large {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-section-modern {
                min-height: 100vh;
                padding: 5rem 1rem 2rem;
            }

            .hero-badge-modern {
                font-size: 0.6875rem;
                padding: 0.5rem 0.875rem;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .hero-badge-modern svg {
                width: 0.875rem;
                height: 0.875rem;
            }

            .hero-title-modern {
                font-size: clamp(1.5rem, 9vw, 2rem);
                margin-bottom: 0.875rem;
            }

            .hero-subtitle-modern {
                font-size: 0.875rem;
                margin-bottom: 1.5rem;
            }

            .hero-btn-primary,
            .hero-btn-secondary {
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
                gap: 0.5rem;
            }

            .product-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .product-price-tag {
                font-size: 1.375rem;
            }

            .product-tags {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .product-badge-new {
                font-size: 0.6875rem;
                padding: 0.25rem 0.625rem;
            }

            .category-name {
                font-size: 1.25rem;
                padding: 0.375rem 1rem;
            }
        }

        /* Additional Mobile Navigation Fixes */
        @media (max-width: 640px) {
            nav {
                width: 96% !important;
                max-width: calc(100% - 1rem) !important;
            }
        }
        
        @media (max-width: 380px) {
            nav {
                width: 98% !important;
            }
            
            nav .shrink-0 a {
                font-size: 1.125rem !important;
            }
            
            nav svg {
                width: 1.5rem !important;
                height: 1.5rem !important;
            }
        }

        /* Smooth Animations */
        @media (prefers-reduced-motion: no-preference) {
            .product-modern-card,
            .feature-card,
            .contact-card,
            .category-block {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category filter pills
            const filterPills = document.querySelectorAll('.filter-pill');
            const categoryBlocks = document.querySelectorAll('.category-block');
            
            // Category filter functionality
            filterPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    const selectedCategory = this.getAttribute('data-category');
                    
                    // Update active pill
                    filterPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter category blocks with animation
                    categoryBlocks.forEach(block => {
                        const blockCategory = block.getAttribute('data-category-block');
                        
                        if (selectedCategory === 'all') {
                            // Show all
                            block.style.display = 'block';
                            setTimeout(() => {
                                block.style.opacity = '1';
                                block.style.transform = 'translateY(0)';
                            }, 10);
                        } else if (blockCategory === selectedCategory) {
                            // Show matching
                            block.style.display = 'block';
                            setTimeout(() => {
                                block.style.opacity = '1';
                                block.style.transform = 'translateY(0)';
                            }, 10);
                        } else {
                            // Hide non-matching
                            block.style.opacity = '0';
                            block.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                block.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });
            
            // Smooth scrolling for anchor links and hero buttons
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href === '#' || !href) return;
                    
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offset = 80; // Offset for fixed header
                        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Add to cart functionality for quick-add buttons
            document.querySelectorAll('.quick-add-btn').forEach(btn => {
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
                            // Show success state
                            this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                            this.style.background = '#10b981';
                            this.style.color = 'white';
                            
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
                                this.style.background = 'white';
                                this.style.color = '#d4af37';
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
