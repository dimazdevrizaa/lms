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
                    <label class="form-label fw-semibold">NIS</label>
                    <input class="form-control" name="nis" value="{{ old('nis', $student->nis) }}" required>
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
