<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Henzo Sushi</title>
    
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
        
        .otp-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 2rem;
            text-align: center;
            letter-spacing: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .otp-input:focus {
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
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: transparent;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border: 2px solid #667eea;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
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
        
        .countdown {
            color: #667eea;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .loading {
            display: none;
        }
        
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                <div class="sushi-emoji mb-4">📧</div>
                <h1 class="text-4xl font-bold text-white mb-2">Verify Your Email</h1>
                <p class="text-white/80 text-lg">Enter the OTP code sent to your email</p>
            </div>

            <!-- Verification Form -->
            <div class="form-container rounded-2xl p-8">
                <form id="verificationForm" class="space-y-6">
            @csrf

                    <!-- Email Address (Read-only) -->
                    <div class="input-group">
                        <div class="input-icon">📧</div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $email ?? request('email')) }}" 
                            required 
                            readonly
                            disabled
                            class="input-field"
                            style="background-color: #f9fafb; color: #6b7280; cursor: not-allowed;"
                        />
                        <div class="text-xs text-gray-500 mt-1 ml-12">
                            📌 Email address for verification (cannot be changed)
                        </div>
                    </div>
                    
                    <!-- OTP Code -->
                    <div class="input-group">
                        <input 
                            id="otp" 
                            type="text" 
                            name="otp" 
                            required 
                            maxlength="6"
                            placeholder="000000"
                            class="otp-input"
                        />
                        <div id="otp-error" class="error-message" style="display: none;"></div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" id="verifyBtn" class="btn-primary">
                        <span class="loading">
                            <span class="spinner"></span>
                            Verifying...
                        </span>
                        <span class="normal">Verify Email</span>
                    </button>
                    
                    <!-- Resend Button -->
                    <button type="button" id="resendBtn" class="btn-secondary">
                        Resend OTP
            </button>
                    
                    <!-- Countdown -->
                    <div id="countdown" class="text-center countdown" style="display: none;">
                        Resend available in <span id="timer">60</span> seconds
                    </div>
        </form>
                
                <!-- Back to Login -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold transition-colors">
                        ← Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            const emailInput = document.getElementById('email');
            const otpInput = document.getElementById('otp');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendBtn = document.getElementById('resendBtn');
            const countdownDiv = document.getElementById('countdown');
            const timerSpan = document.getElementById('timer');
            const otpError = document.getElementById('otp-error');
            
            let countdown = 0;
            let countdownInterval = null;
            
            // Auto-format OTP input
            otpInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 6) value = value.slice(0, 6);
                e.target.value = value;
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                verifyOtp();
            });
            
            // Resend button
            resendBtn.addEventListener('click', function() {
                resendOtp();
            });
            
            function verifyOtp() {
                const email = emailInput.value;
                const otp = otpInput.value;
                
                if (!email || !otp) {
                    showError('Please fill in all fields');
                    return;
                }
                
                if (otp.length !== 6) {
                    showError('Please enter a 6-digit OTP code');
                    return;
                }
                
                setLoading(true);
                
                fetch('{{ route("otp.verify.post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email, otp })
                })
                .then(response => response.json())
                .then(data => {
                    setLoading(false);
                    if (data.success) {
                        showSuccess(data.message);
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    setLoading(false);
                    showError('An error occurred. Please try again.');
                });
            }
            
            function resendOtp() {
                const email = emailInput.value;
                
                if (!email) {
                    showError('Email address is required');
                    return;
                }
                
                setLoading(true);
                
                fetch('{{ route("otp.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email })
                })
                .then(response => response.json())
                .then(data => {
                    setLoading(false);
                    if (data.success) {
                        showSuccess(data.message);
                        startCountdown();
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    setLoading(false);
                    showError('An error occurred. Please try again.');
                });
            }
            
            function setLoading(loading) {
                verifyBtn.disabled = loading;
                resendBtn.disabled = loading;
                
                if (loading) {
                    verifyBtn.querySelector('.loading').style.display = 'inline';
                    verifyBtn.querySelector('.normal').style.display = 'none';
                } else {
                    verifyBtn.querySelector('.loading').style.display = 'none';
                    verifyBtn.querySelector('.normal').style.display = 'inline';
                }
            }
            
            function showError(message) {
                otpError.textContent = message;
                otpError.style.display = 'block';
                setTimeout(() => {
                    otpError.style.display = 'none';
                }, 5000);
            }
            
            function showSuccess(message) {
                // You can implement a success notification here
                console.log('Success:', message);
            }
            
            function startCountdown() {
                countdown = 60;
                countdownDiv.style.display = 'block';
                resendBtn.style.display = 'none';
                
                countdownInterval = setInterval(() => {
                    countdown--;
                    timerSpan.textContent = countdown;
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        countdownDiv.style.display = 'none';
                        resendBtn.style.display = 'block';
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>