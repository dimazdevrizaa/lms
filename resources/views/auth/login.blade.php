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
        <div class="form-group">
            <label for="remember_me" class="remember-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span class="remember-label">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="login-actions">
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
        </div>
    </form>



    <div class="parent-access-divider">
        <p class="parent-access-label">Apakah Anda Orang Tua Siswa?</p>
        <a href="{{ route('parent.index') }}" class="btn-parent-access">
            🔑 Pantau Aktivitas Anak (Orang Tua)
        </a>
    </div>

    @push('styles')
    <style>
        .login-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
            gap: 1rem;
        }
        .parent-access-divider {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(27, 94, 32, 0.08);
            text-align: center;
        }
        .parent-access-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }
        .btn-parent-access {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.8rem 1.5rem;
            background-color: var(--accent);
            color: var(--primary);
            border: 1px solid rgba(249, 168, 37, 0.4);
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 12px rgba(249, 168, 37, 0.15);
        }
        .btn-parent-access:hover {
            background-color: #F57F17;
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(249, 168, 37, 0.25);
        }
    </style>
    @endpush
</x-guest-layout>
