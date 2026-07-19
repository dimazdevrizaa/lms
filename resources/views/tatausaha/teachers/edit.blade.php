@extends('layouts.lms')

@section('title', 'Edit Guru')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center gap-3 mb-4 reveal">
        <a href="{{ route('tatausaha.teachers.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 0;">
            Edit Guru
        </h1>
    </div>

    {{-- Form Card --}}
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <h2 class="content-card-title">Edit Data Guru</h2>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('tatausaha.teachers.update', $teacher) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $teacher->user?->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $teacher->user?->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NIP (opsional)</label>
                    <input class="form-control" name="nip" value="{{ old('nip', $teacher->nip) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">No HP (opsional)</label>
                    <input class="form-control" name="phone" value="{{ old('phone', $teacher->phone) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Ganti Password (Kosongkan jika tidak ingin ganti)</label>
                    <x-text-input class="form-control" type="password" name="password" />
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary-theme" type="submit">
                        <i class="fas fa-save me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
