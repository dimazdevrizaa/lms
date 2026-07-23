<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Guest CSS stylesheet -->
        <link rel="stylesheet" href="{{ asset('css/guest.css') }}">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @stack('styles')
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

            <!-- Footer credit -->
            <p style="color: rgba(255,255,255,0.35); font-size: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500; letter-spacing: 0.02em; margin: 0;">
                &copy; {{ date('Y') }} LMS SMA Negeri 15 Padang
            </p>
        </div>

        <script>
            function togglePasswordVisibility(inputId, button) {
                const input = document.getElementById(inputId);
                if (!input) return;
                const icon = button.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) icon.className = 'far fa-eye-slash';
                } else {
                    input.type = 'password';
                    if (icon) icon.className = 'far fa-eye';
                }
            }
        </script>
        @stack('scripts')
    </body>
</html>
