<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                background: linear-gradient(135deg, #25671E 0%, #48A111 100%) !important;
                font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: #1F2937;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            .login-container {
                width: 100%;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }

            .logo-wrapper {
                flex-shrink: 0;
            }

            .logo-wrapper img {
                width: 7rem;
                height: 7rem;
                object-fit: contain;
                border-radius: 50%;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                background-color: #FFFFFF;
                padding: 0.5rem;
                display: block;
            }

            .form-box {
                width: 100%;
                max-width: 28rem;
                background-color: #FFFFFF;
                border-radius: 0.5rem;
                box-shadow: 0 12.5px 50px rgba(0, 0, 0, 0.15);
                border-top: 4px solid #25671E;
                padding: 2rem;
            }

            .form-label {
                display: block;
                font-weight: 500;
                font-size: 0.875rem;
                color: #25671E;
                margin-top: 1rem;
                margin-bottom: 0.5rem;
            }

            .form-input {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid #D1D5DB;
                border-radius: 0.375rem;
                font-size: 1rem;
                color: #1F2937;
                background-color: #FFFFFF;
                font-family: inherit;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                transition: all 150ms ease-in-out;
            }

            .form-input::placeholder {
                color: #9CA3AF;
            }

            .form-input:focus {
                outline: none;
                border-color: #25671E;
                box-shadow: 0 0 0 3px rgba(37, 103, 30, 0.1);
            }

            .btn-login {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1rem;
                background-color: #25671E;
                color: white;
                border: none;
                border-radius: 0.375rem;
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                cursor: pointer;
                transition: all 150ms ease-in-out;
                font-family: inherit;
            }

            .btn-login:hover {
                background-color: #48A111;
            }

            .btn-login:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(242, 181, 11, 0.3);
            }

            .page-heading {
                margin-bottom: 1.5rem;
                text-align: center;
                padding: 0;
            }

            .page-heading h2 {
                font-size: 1.875rem;
                font-weight: 700;
                color: #25671E;
                margin: 0 0 0.5rem 0;
            }

            .page-heading p {
                font-size: 0.875rem;
                color: #6B7280;
                margin: 0;
            }

            .form-error {
                color: #DC2626;
                font-size: 0.875rem;
                margin-top: 0.5rem;
            }

            form {
                width: 100%;
            }

            .button-group {
                display: flex;
                gap: 1rem;
                align-items: center;
                justify-content: space-between;
                margin-top: 1.5rem;
                flex-wrap: wrap;
            }

            .forgot-link {
                color: #25671E;
                text-decoration: underline;
                font-size: 0.875rem;
                transition: color 150ms ease-in-out;
                background: none;
                border: none;
                cursor: pointer;
                font-family: inherit;
                padding: 0;
                margin: 0;
            }

            .forgot-link:hover {
                color: #48A111;
            }
        </style>

        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo-wrapper">
                <a href="/">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" />
                </a>
            </div>

            <div class="form-box">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
