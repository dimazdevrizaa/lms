<x-guest-layout>
    <!-- Page Title -->
    <div class="page-heading">
        <h2>{{ __('Registrasi Akun Baru') }}</h2>
        <p>{{ __('Isi data diri untuk mendaftar ke sistem') }}</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 text-danger small">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Role Selector -->
        <div class="form-group">
            <label for="role" class="form-label">{{ __('Daftar Sebagai') }}</label>
            <select id="role" class="form-input" name="role" required onchange="toggleIdentifierLabel()" style="background-color: var(--bg-input) !important; color: var(--text-dark) !important; border: 1px solid var(--border-color); width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.95rem; font-family: 'Inter', sans-serif;">
                <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>{{ __('Siswa') }}</option>
                <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>{{ __('Guru') }}</option>
            </select>
        </div>

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
            <input 
                id="name" 
                class="form-input" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                placeholder="Masukkan nama lengkap Anda"
            />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email / Username') }}</label>
            <input 
                id="email" 
                class="form-input" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                placeholder="Masukkan email Anda"
            />
        </div>

        <!-- Identifier (NISN or NIP/NUPTK) -->
        <div class="form-group">
            <label for="identifier" class="form-label" id="identifierLabel">{{ __('NISN (Nomor Induk Siswa Nasional)') }}</label>
            <input 
                id="identifier" 
                class="form-input" 
                type="text" 
                name="identifier" 
                value="{{ old('identifier') }}" 
                required 
                placeholder="Masukkan nomor identitas"
            />
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
                placeholder="Buat password baru"
            />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
            <input 
                id="password_confirmation" 
                class="form-input"
                type="password"
                name="password_confirmation"
                required 
                placeholder="Masukkan kembali password"
            />
        </div>

        <div class="login-actions">
            <a class="forgot-link" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Login') }}
            </a>

            <button type="submit" class="btn-login">
                {{ __('Daftar') }}
            </button>
        </div>
    </form>

    @push('styles')
    <style>
        .login-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
            gap: 1rem;
        }
    </style>
    @endpush

    <script>
        function toggleIdentifierLabel() {
            const role = document.getElementById('role').value;
            const label = document.getElementById('identifierLabel');
            const input = document.getElementById('identifier');
            if (role === 'guru') {
                label.textContent = "{{ __('NUPTK / NIP') }}";
                input.placeholder = "{{ __('Masukkan NUPTK atau NIP Anda') }}";
            } else {
                label.textContent = "{{ __('NISN (Nomor Induk Siswa Nasional)') }}";
                input.placeholder = "{{ __('Masukkan NISN Anda') }}";
            }
        }
        document.addEventListener('DOMContentLoaded', toggleIdentifierLabel);
    </script>
</x-guest-layout>
