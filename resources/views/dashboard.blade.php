<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<div class="container py-8">
    <div class="text-center mb-8">
        <h1 class="font-display" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;">
            Welcome to Your Dashboard, {{ Auth::user()->name }}! 👋
        </h1>
        <p style="font-size: 1.2rem; color: var(--text-light);">
            Manage your orders, track deliveries, and explore our menu.
        </p>
    </div>

    <!-- User Role-based Content -->
    @if(Auth::user()->hasRole('admin'))
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Analytics</h3>
                <p style="color: var(--text-light);">View sales reports and statistics</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Manage Users</h3>
                <p style="color: var(--text-light);">Add chefs and delivery staff</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🍣</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Menu Management</h3>
                <p style="color: var(--text-light);">Add and edit menu items</p>
            </div>
        </div>
    @elseif(Auth::user()->hasRole('chef'))
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🍱</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">New Orders</h3>
                <p style="color: var(--text-light);">View and accept new orders</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👨‍🍳</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">My Orders</h3>
                <p style="color: var(--text-light);">Track your assigned orders</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Menu</h3>
                <p style="color: var(--text-light);">View available menu items</p>
            </div>
        </div>
    @elseif(Auth::user()->hasRole('delivery'))
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🚚</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Delivery Orders</h3>
                <p style="color: var(--text-light);">View assigned deliveries</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📍</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Location Tracking</h3>
                <p style="color: var(--text-light);">Update your location</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Status Updates</h3>
                <p style="color: var(--text-light);">Mark deliveries as complete</p>
            </div>
        </div>
    @else
        <!-- Customer Dashboard -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🍣</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Order Now</h3>
                <p style="color: var(--text-light);">Browse menu and place orders</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">My Orders</h3>
                <p style="color: var(--text-light);">Track your order status</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">❤️</div>
                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Favorites</h3>
                <p style="color: var(--text-light);">Your favorite dishes</p>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div style="margin-top: 60px; text-align: center;">
        <h2 class="font-display" style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 2rem;">
            Quick Actions
        </h2>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 30px;">
                🏠 Back to Home
            </a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline" style="font-size: 1.1rem; padding: 15px 30px;">
                👤 Edit Profile
            </a>
        </div>
    </div>
</div>
</x-app-layout>