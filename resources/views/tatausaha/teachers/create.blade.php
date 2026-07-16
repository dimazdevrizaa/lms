@extends('layouts.lms')

@section('title', 'Tambah Guru')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center gap-3 mb-4 reveal">
        <a href="{{ route('tatausaha.teachers.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 0;">
            Tambah Guru
        </h1>
    </div>

    {{-- Form Card --}}
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="content-card-title">Data Guru Baru</h2>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('tatausaha.teachers.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NIP (opsional)</label>
                    <input class="form-control" name="nip" value="{{ old('nip') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">No HP (opsional)</label>
                    <input class="form-control" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary-theme" type="submit">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
            <p class="text-muted mt-3 mb-0 small">
                <i class="fas fa-info-circle me-1"></i> Password default akun guru: <strong>password</strong>
            </p>
        </div>
    </div>
@endsection
