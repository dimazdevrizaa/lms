@extends('layouts.lms')

@section('title', 'Edit Guru')

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('tatausaha.teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-0">Edit Guru</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tatausaha.teachers.update', $teacher) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $teacher->user?->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $teacher->user?->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIP (opsional)</label>
                    <input class="form-control" name="nip" value="{{ old('nip', $teacher->nip) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">No HP (opsional)</label>
                    <input class="form-control" name="phone" value="{{ old('phone', $teacher->phone) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label">Ganti Password (Kosongkan jika tidak ingin ganti)</label>
                    <input class="form-control" type="password" name="password">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
