<x-guest-layout>
    <!-- Page Title -->
    <div class="page-heading">
        <h2>{{ __('Verifikasi Email') }}</h2>
        <p>{{ __('Silakan verifikasi email Anda') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background-color: #DBEAFE; color: #065F46; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem; border-left: 4px solid #059669;">
            {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
        </div>
    @endif

    <div style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn-login">
                {{ __('Kirim Ulang Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="forgot-link" style="text-decoration: underline; background: none; border: none; cursor: pointer; padding: 0;">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
