@extends('layouts.lms')

@section('title', 'Tambah Guru')

@section('content')
    <h1 class="h3 mb-3">Tambah Guru</h1>

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
                    <a class="btn btn-outline-secondary" href="{{ route('tatausaha.teachers.index') }}">Kembali</a>
                </div>
            </form>
            <p class="text-muted mt-3 mb-0">Password default akun guru: <strong>password</strong></p>
        </div>
    </div>
@endsection

