<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                                    🍣 Henzo Sushi Admin
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                    📊 Dashboard
                                </a>
                                <a href="{{ route('admin.users') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.users*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    👥 Users
                                </a>
                                <a href="{{ route('admin.products') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.products*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    🍣 Menu
                                </a>
                                <a href="{{ route('admin.categories') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.categories*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    🗂️ Categories
                                </a>
                                <a href="{{ route('admin.orders') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.orders*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    📋 Orders
                                </a>
                                <a href="{{ route('admin.promo-codes') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.promo-codes*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    🎟️ Promo Codes
                                </a>
                                <a href="{{ route('admin.schedules') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.schedules*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    📅 Schedule
                                </a>
                                <a href="{{ route('admin.settings') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('admin.settings*') ? 'border-blue-500 text-gray-900' : '' }}">
                                    ⚙️ Settings
                                </a>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        👑 Admin
                                    </span>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>


