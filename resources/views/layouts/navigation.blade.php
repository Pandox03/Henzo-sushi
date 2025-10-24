    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50" style="overflow: visible;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold text-yellow-600 hover:text-yellow-700 transition-colors">
                        🍣 <span class="hidden sm:inline">Henzo Sushi</span><span class="sm:hidden">Henzo</span>
                    </a>
                </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-4 lg:space-x-8 sm:-my-px sm:ms-6 lg:ms-10 sm:flex">
                            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                {{ __('Home') }}
                            </x-nav-link>
                            @auth
                                @if(!Auth::user()->hasRole('chef'))
                                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                                        {{ __('My Orders') }}
                                    </x-nav-link>
                                @endif
                            @endauth
                        </div>
            </div>

            <!-- Right side navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                    <!-- Admin Navigation -->
                    <div class="mr-4 flex space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Admin
                        </a>
                    </div>
                @elseif(Auth::check() && Auth::user()->hasRole('chef'))
                    <!-- Chef Navigation -->
                    <div class="mr-4 flex space-x-4">
                        <a href="{{ route('chef.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Chef Dashboard
                        </a>
                        <a href="{{ route('chef.orders.history') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Order History
                        </a>
                    </div>
                @elseif(Auth::check() && Auth::user()->hasRole('delivery'))
                    <!-- Delivery Navigation -->
                    <div class="mr-4 flex space-x-4">
                        <a href="{{ route('delivery.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Delivery Dashboard
                        </a>
                        <a href="{{ route('delivery.history') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Delivery History
                        </a>
                    </div>
                @else
                    <!-- Cart Icon -->
                    <div class="mr-4 relative cart-container">
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors flex items-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
                            </svg>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg border-2 border-white" style="display: none;">0</span>
                        </a>
                    </div>
                @endif
                
                @auth
                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Cart & Hamburger -->
            <div class="-me-2 flex items-center space-x-2 sm:hidden">
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                    <!-- Mobile Admin Navigation -->
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-2 py-1 rounded-md text-sm font-medium">
                        Admin
                    </a>
                @elseif(Auth::check() && Auth::user()->hasRole('chef'))
                    <!-- Mobile Chef Navigation -->
                    <a href="{{ route('chef.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-2 py-1 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                @elseif(Auth::check() && Auth::user()->hasRole('delivery'))
                    <!-- Mobile Delivery Navigation -->
                    <a href="{{ route('delivery.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-2 py-1 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                @else
                    <!-- Mobile Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors flex items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
                        </svg>
                        <span id="cart-count-mobile" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg border-2 border-white" style="display: none;">0</span>
                    </a>
                @endif
                
                <!-- Hamburger Menu -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    @auth
                        @if(Auth::user()->hasRole('admin'))
                            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('Admin') }}
                            </x-responsive-nav-link>
                        @elseif(Auth::user()->hasRole('chef'))
                            <x-responsive-nav-link :href="route('chef.dashboard')" :active="request()->routeIs('chef.dashboard')">
                                {{ __('Chef Dashboard') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('chef.orders.history')" :active="request()->routeIs('chef.orders.history')">
                                {{ __('Order History') }}
                            </x-responsive-nav-link>
                        @elseif(Auth::user()->hasRole('delivery'))
                            <x-responsive-nav-link :href="route('delivery.dashboard')" :active="request()->routeIs('delivery.dashboard')">
                                {{ __('Delivery Dashboard') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('delivery.history')" :active="request()->routeIs('delivery.history')">
                                {{ __('Delivery History') }}
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                                {{ __('My Orders') }}
                            </x-responsive-nav-link>
                        @endif
                    @endauth
                </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest responsive navigation -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-yellow-500 text-white hover:bg-yellow-600">
                        {{ __('Register') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>

