    <!-- Island Style Navigation - Apple Glass Effect -->
    <nav x-data="{ open: false }" 
         x-init="$watch('open', value => document.body.classList.toggle('menu-open', value))"
         class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] sm:w-auto bg-white/80 backdrop-blur-xl shadow-2xl rounded-full border border-white/20 max-w-6xl" 
         style="backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">

            <!-- Primary Navigation Menu -->
            <div class="px-6 sm:px-8">
                <div class="flex justify-between h-14 sm:h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-gray-800 hover:text-yellow-600 transition-colors">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h2v2h-2v-2zm-4 0h2v2h-2v-2z"/>
                        </svg>
                        <span class="hidden sm:inline">Henzo Sushi</span><span class="sm:hidden">Henzo</span>
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
                    <div class="flex space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all">
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

    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 sm:hidden"
         style="display: none;">
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 h-full w-80 max-w-[85%] bg-white shadow-2xl z-50 overflow-y-auto sm:hidden"
         style="display: none;">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h2v2h-2v-2zm-4 0h2v2h-2v-2z"/>
                </svg>
                <span class="text-xl font-bold text-gray-800">Henzo Sushi</span>
            </div>
            <button @click="open = false" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Content -->
        <div class="p-6">
            @auth
                <!-- User Info -->
                <div class="mb-6 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                </div>
            @endauth

            <!-- Navigation Links -->
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('home') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>

                @auth
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('admin.*') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Admin</span>
                        </a>
                    @elseif(Auth::user()->hasRole('chef'))
                        <a href="{{ route('chef.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('chef.dashboard') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Chef Dashboard</span>
                        </a>
                        <a href="{{ route('chef.orders.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('chef.orders.history') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Order History</span>
                        </a>
                    @elseif(Auth::user()->hasRole('delivery'))
                        <a href="{{ route('delivery.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('delivery.dashboard') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            <span>Delivery Dashboard</span>
                        </a>
                        <a href="{{ route('delivery.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('delivery.history') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Delivery History</span>
                        </a>
                    @else
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('orders.*') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>My Orders</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors {{ request()->routeIs('profile.edit') ? 'bg-yellow-50 text-yellow-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                @endauth
            </div>

            <!-- Auth Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </button>
                    </form>
                @else
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-gray-700 border-2 border-gray-300 hover:border-yellow-500 hover:text-yellow-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 text-white hover:from-yellow-600 hover:to-yellow-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Register</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

