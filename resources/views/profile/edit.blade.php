@extends('layouts.lms')

@section('title', 'Pengaturan Profil')

@section('content')
    <div class="mb-5 reveal">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: var(--secondary);">Dashboard</a></li>
                <li class="breadcrumb-item active">Profil Saya</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary) !important;">Pengaturan Profil</h1>
        <p class="text-muted small">Kelola informasi akun Anda dan ubah kata sandi di sini.</p>
    </div>

    <div style="max-width: 800px; margin: 0 auto; padding-bottom: 2rem;">
        <!-- Profile Information Card -->
        <div class="content-card reveal reveal-delay-1 mb-4" style="border-radius: var(--radius-md) !important;">
            <div class="content-card-header">
                <div class="content-card-header-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h5 class="content-card-title">Informasi Profil</h5>
            </div>
            <div class="content-card-body p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="content-card reveal reveal-delay-2 mb-4" style="border-radius: var(--radius-md) !important;">
            <div class="content-card-header">
                <div class="content-card-header-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h5 class="content-card-title">Ubah Password</h5>
            </div>
            <div class="content-card-body p-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- ponytail: Hapus Akun is intentionally hidden to preserve LMS data integrity (grades, submissions, attendances) --}}
    </div>
@endsection
