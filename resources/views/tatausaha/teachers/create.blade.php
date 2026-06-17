@extends('layouts.lms')

@section('title', 'Tambah Guru')

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('tatausaha.teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-0">Tambah Guru</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tatausaha.teachers.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIP (opsional)</label>
                    <input class="form-control" name="nip" value="{{ old('nip') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">No HP (opsional)</label>
                    <input class="form-control" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
            <p class="text-muted mt-3 mb-0">Password default akun guru: <strong>password</strong></p>
        </div>
    </div>
@endsection

