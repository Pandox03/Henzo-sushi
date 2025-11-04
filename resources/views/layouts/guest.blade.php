<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Henzo Sushi') }} - Authentic Japanese Cuisine</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            * {
                box-sizing: border-box;
            }

            :root {
                --primary: #d4af37;
                --primary-dark: #b8941f;
                --text-dark: #1a202c;
                --text-light: #64748b;
            }

            body {
                overflow-x: hidden;
                margin: 0;
                padding: 0;
                background: #fafbfc;
            }

            .hidden {
                display: none;
            }

            /* Auth Layout - Light Mode */
            .auth-layout {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.5rem;
                background: #fafbfc;
            }

            .auth-container {
                width: 100%;
                max-width: 480px;
                animation: fadeInUp 0.8s ease-out;
            }

            .auth-container.wide {
                max-width: 900px;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .auth-logo-wrapper {
                text-align: center;
                margin-bottom: 1.5rem;
            }

            .auth-logo-link {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                text-decoration: none;
                transition: transform 0.3s ease;
            }

            .auth-logo-link:hover {
                transform: scale(1.05);
            }

            .auth-logo-icon {
                width: 3rem;
                height: 3rem;
                color: var(--primary);
                filter: drop-shadow(0 4px 8px rgba(212, 175, 55, 0.3));
            }

            .auth-logo-text {
                font-family: 'Playfair Display', serif;
                font-size: 2rem;
                font-weight: 700;
                background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .auth-card {
                background: white;
                border-radius: 24px;
                padding: 2rem 2rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.04);
                border: 1px solid #e2e8f0;
            }

            /* Auth Form Styles */
            .auth-form-header {
                text-align: center;
                margin-bottom: 1.5rem;
            }

            .auth-form-title {
                font-family: 'Playfair Display', serif;
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 0.375rem;
            }

            .auth-form-subtitle {
                color: var(--text-light);
                font-size: 0.9375rem;
            }

            .auth-alert {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.875rem 1rem;
                border-radius: 12px;
                margin-bottom: 1.25rem;
                font-size: 0.875rem;
            }

            .auth-alert-success {
                background: #d1fae5;
                color: #065f46;
                border: 1px solid #a7f3d0;
            }

            .auth-alert-icon {
                width: 1.25rem;
                height: 1.25rem;
                flex-shrink: 0;
            }

            .auth-form {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.25rem;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .form-label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                color: var(--text-dark);
            }

            .form-label-icon {
                width: 1rem;
                height: 1rem;
                color: var(--primary);
            }

            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                font-size: 0.9375rem;
                color: var(--text-dark);
                transition: all 0.3s ease;
                background: #f8fafc;
            }

            .form-input:focus {
                outline: none;
                border-color: var(--primary);
                background: white;
                box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            }

            .form-input::placeholder {
                color: #94a3b8;
            }

            .form-input-error {
                border-color: #ef4444;
                background: #fef2f2;
            }

            .form-input-error:focus {
                border-color: #ef4444;
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            }

            .password-input-wrapper {
                position: relative;
            }

            .password-toggle {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #64748b;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .password-toggle:hover {
                color: var(--primary);
                background: rgba(212, 175, 55, 0.1);
            }

            .form-error {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #ef4444;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }

            .form-error-icon {
                width: 1rem;
                height: 1rem;
                flex-shrink: 0;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .checkbox-label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                cursor: pointer;
            }

            .checkbox-input {
                width: 1.125rem;
                height: 1.125rem;
                border-radius: 6px;
                border: 2px solid #e2e8f0;
                cursor: pointer;
                accent-color: var(--primary);
            }

            .checkbox-text {
                font-size: 0.9375rem;
                color: #64748b;
                user-select: none;
            }

            .forgot-password-link {
                color: var(--primary);
                text-decoration: none;
                font-size: 0.9375rem;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .forgot-password-link:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            .submit-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.625rem;
                width: 100%;
                padding: 0.875rem 1.5rem;
                background: linear-gradient(135deg, var(--primary) 0%, #f4d03f 100%);
                color: #0f172a;
                border: none;
                border-radius: 10px;
                font-weight: 700;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
                margin-top: 0.25rem;
            }

            .submit-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
            }

            .submit-btn:active {
                transform: translateY(0);
            }

            .submit-btn-icon {
                width: 1.25rem;
                height: 1.25rem;
                transition: transform 0.3s ease;
            }

            .submit-btn:hover .submit-btn-icon {
                transform: translateX(4px);
            }

            .auth-footer {
                text-align: center;
                padding-top: 1.25rem;
                margin-top: 0.25rem;
                border-top: 1px solid #e2e8f0;
            }

            .auth-footer-text {
                color: #64748b;
                font-size: 0.9375rem;
            }

            .auth-footer-link {
                color: var(--primary);
                text-decoration: none;
                font-weight: 700;
                font-size: 0.9375rem;
                margin-left: 0.5rem;
                transition: color 0.3s ease;
            }

            .auth-footer-link:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            /* Role Selector Styles */
            .role-selector {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.875rem;
            }

            .role-option {
                cursor: pointer;
            }

            .role-radio {
                position: absolute;
                opacity: 0;
                pointer-events: none;
            }

            .role-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1rem 0.75rem;
                background: #f8fafc;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .role-option:hover .role-card {
                border-color: var(--primary);
                background: white;
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(212, 175, 55, 0.15);
            }

            .role-option-selected .role-card,
            .role-radio:checked + .role-card {
                border-color: var(--primary);
                background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(244, 208, 63, 0.1) 100%);
                box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
            }

            .role-icon {
                width: 2.5rem;
                height: 2.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: white;
                border-radius: 10px;
                margin-bottom: 0.625rem;
                color: var(--primary);
                transition: all 0.3s ease;
            }

            .role-option:hover .role-icon {
                transform: scale(1.1);
            }

            .role-icon svg {
                width: 1.5rem;
                height: 1.5rem;
            }

            .role-title {
                font-weight: 700;
                font-size: 0.9375rem;
                color: var(--text-dark);
                margin-bottom: 0.125rem;
            }

            .role-desc {
                font-size: 0.75rem;
                color: var(--text-light);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .auth-layout {
                    padding: 2rem 1rem;
                }

                .auth-card {
                    padding: 1.5rem 1.25rem;
                }

                .auth-logo-text {
                    font-size: 1.5rem;
                }

                .auth-form-title {
                    font-size: 1.5rem;
                }

                .auth-logo-icon {
                    width: 2.25rem;
                    height: 2.25rem;
                }

                .form-row {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }
            }

            @media (max-width: 640px) {
                .role-selector {
                    grid-template-columns: 1fr;
                }

                .form-options {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="auth-layout">
            <div class="auth-container">
                <div class="auth-logo-wrapper">
                    <a href="{{ route('home') }}" class="auth-logo-link">
                        <svg class="auth-logo-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h2v2h-2v-2zm-4 0h2v2h-2v-2z"/>
                        </svg>
                        <span class="auth-logo-text">Henzo Sushi</span>
                    </a>
                </div>

                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
