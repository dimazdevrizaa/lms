@extends('layouts.lms')

@section('title', 'Edit Siswa')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center gap-3 mb-4 reveal">
        <a href="{{ route('tatausaha.students.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 0;">
            Edit Siswa
        </h1>
    </div>

    {{-- Form Card --}}
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <h2 class="content-card-title">Edit Data Siswa</h2>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('tatausaha.students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $student->user?->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $student->user?->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NISN</label>
                    <input class="form-control" name="nisn" value="{{ old('nisn', $student->nisn) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select class="form-select" name="class_id">
                        <option value="">-</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Ganti Password (Kosongkan jika tidak ingin ganti)</label>
                    <div class="position-relative">
                        <x-text-input id="tu_student_password" class="form-control pe-5" type="password" name="password" placeholder="Kosongkan jika tidak diubah" />
                        <button type="button" class="btn btn-link text-muted position-absolute end-0 top-50 translate-middle-y me-2 text-decoration-none shadow-none" onclick="togglePasswordVisibility('tu_student_password', this)">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <x-password-strength-meter inputId="tu_student_password" />
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
