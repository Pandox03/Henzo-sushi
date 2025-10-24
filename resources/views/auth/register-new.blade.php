<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Henzo Sushi</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sushi-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .sushi-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
        }
        
        .sushi-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px, 30px 30px;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .form-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .input-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .select-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        .select-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.2rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .sushi-emoji {
            font-size: 3rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .floating-sushi {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: floatAround 15s infinite linear;
        }
        
        .floating-sushi:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-sushi:nth-child(2) { top: 20%; right: 15%; animation-delay: 3s; }
        .floating-sushi:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 6s; }
        .floating-sushi:nth-child(4) { bottom: 20%; right: 25%; animation-delay: 9s; }
        
        @keyframes floatAround {
            0% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(90deg); }
            50% { transform: translateY(0px) rotate(180deg); }
            75% { transform: translateY(20px) rotate(270deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .role-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            margin: 0.25rem 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-option:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .role-emoji {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
        
        .role-text {
            flex: 1;
        }
        
        .role-title {
            font-weight: 600;
            color: #374151;
        }
        
        .role-description {
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen sushi-bg flex items-center justify-center p-4">
        <!-- Floating Sushi Emojis -->
        <div class="floating-sushi">🍣</div>
        <div class="floating-sushi">🍱</div>
        <div class="floating-sushi">🍙</div>
        <div class="floating-sushi">🥢</div>
        
        <!-- Sushi Pattern Overlay -->
        <div class="sushi-pattern"></div>
        
        <div class="w-full max-w-md relative z-10">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="sushi-emoji mb-4">🍣</div>
                <h1 class="text-4xl font-bold text-white mb-2">Join Henzo Sushi</h1>
                <p class="text-white/80 text-lg">Create your customer account and start ordering delicious sushi</p>
            </div>
            
            <!-- Register Form -->
            <div class="form-container rounded-2xl p-8">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <h4 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- First Name -->
                    <div class="input-group">
                        <div class="input-icon">👤</div>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="given-name"
                            placeholder="Enter your first name"
                            class="input-field"
                        />
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Last Name -->
                    <div class="input-group">
                        <div class="input-icon">👤</div>
                        <input 
                            id="last_name" 
                            type="text" 
                            name="last_name" 
                            value="{{ old('last_name') }}" 
                            required 
                            autocomplete="family-name"
                            placeholder="Enter your last name"
                            class="input-field"
                        />
                        @error('last_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email Address -->
                    <div class="input-group">
                        <div class="input-icon">📧</div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email address"
                            class="input-field"
                        />
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Phone Number -->
                    <div class="input-group">
                        <div class="input-icon">📞</div>
                        <input 
                            id="phone" 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required 
                            autocomplete="tel"
                            placeholder="Enter your phone number"
                            class="input-field"
                        />
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Address -->
                    <div class="input-group">
                        <div class="input-icon">📍</div>
                        <textarea 
                            id="address" 
                            name="address" 
                            required 
                            autocomplete="street-address"
                            placeholder="Enter your full address"
                            class="input-field"
                            rows="3"
                            style="resize: vertical; min-height: 80px;"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="input-group">
                        <div class="input-icon">🔒</div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                            class="input-field"
                        />
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="input-group">
                        <div class="input-icon">🔐</div>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                            class="input-field"
                        />
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Hidden role field - always customer -->
                    <input type="hidden" name="role" value="customer">
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        Create Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold transition-colors">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
