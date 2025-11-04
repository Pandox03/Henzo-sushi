<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Experience the finest sushi and Japanese cuisine at Henzo Sushi. Fresh ingredients, traditional techniques, and modern presentation.">
        <meta name="keywords" content="sushi, japanese cuisine, restaurant, delivery, fresh fish, authentic">
        <meta name="author" content="Henzo Sushi">

        <title>{{ config('app.name', 'Henzo Sushi') }} - Authentic Japanese Cuisine</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional responsive utilities -->
        <style>
            /* Prevent horizontal scroll on small devices */
            body {
                overflow-x: hidden;
            }
            
            /* Smooth scrolling for anchor links */
            html {
                scroll-behavior: smooth;
            }
            
            /* Remove any gaps between navigation and hero */
            main {
                padding: 0;
                margin: 0;
            }
            
            main > section:first-child {
                margin-top: 0 !important;
                padding-top: 8rem !important;
            }
            
            @media (max-width: 768px) {
                main > section:first-child {
                    padding-top: 6rem !important;
                }
            }
            
            @media (max-width: 640px) {
                main > section:first-child {
                    padding-top: 5rem !important;
                }
            }
            
            /* Touch-friendly button sizes */
            @media (max-width: 768px) {
                button, .btn, a[role="button"] {
                    min-height: 44px;
                    min-width: 44px;
                }
            }
            
            /* Improve text readability on small screens */
            @media (max-width: 480px) {
                body {
                    font-size: 16px;
                    line-height: 1.5;
                }
            }
            
            /* Cart badge styling */
            #cart-count, #cart-count-mobile {
                background: #ef4444 !important;
                color: white !important;
                font-weight: bold !important;
                font-size: 0.75rem !important;
                min-width: 20px !important;
                min-height: 20px !important;
                border: 2px solid white !important;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4) !important;
                z-index: 1000 !important;
            }
            
            /* Ensure cart container doesn't clip the badge */
            .cart-container {
                overflow: visible !important;
                position: relative !important;
            }
            
            /* Cart badge animation */
            #cart-count, #cart-count-mobile {
                animation: cartPulse 0.3s ease-in-out;
            }
            
            @keyframes cartPulse {
                0% { transform: scale(0.8); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            /* Prevent body scroll when mobile menu is open */
            body.menu-open {
                overflow: hidden;
            }
        </style>
        
        <script>
            // Update cart count on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Get current cart count from server
                fetch('{{ route("cart.count") }}')
                    .then(response => response.json())
                    .then(data => {
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
                        
                        console.log('Initial cart count:', data.cart_count);
                        console.log('Cart contents:', data.cart_contents);
                    })
                    .catch(error => {
                        console.error('Error loading cart count:', error);
                    });
            });
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-900">
        <div class="min-h-screen bg-slate-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-slate-800/50 backdrop-blur-lg shadow-lg border-b border-white/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="text-slate-100">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Footer -->
        <x-footer />
    </body>
</html>
