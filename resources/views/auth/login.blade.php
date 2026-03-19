<x-guest-layout>
    <!-- Page Title -->
    <div class="page-heading">
        <h2>{{ __('Selamat Datang') }}</h2>
        <p>{{ __('Silakan masuk ke akun Anda') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input 
                id="email" 
                class="form-input" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="Masukkan email Anda"
            />
            @if ($errors->has('email'))
                <div class="form-error">
                    @foreach ($errors->get('email') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input 
                id="password" 
                class="form-input"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="Masukkan password Anda"
            />
            @if ($errors->has('password'))
                <div class="form-error">
                    @foreach ($errors->get('password') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div style="margin-top: 1.25rem;">
            <label for="remember_me" class="remember-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span class="remember-label">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem; gap: 1rem;">
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @else
                <div></div>
            @endif

            <button type="submit" class="btn-login">
                {{ __('Login') }}
            </button>
</x-guest-layout>
