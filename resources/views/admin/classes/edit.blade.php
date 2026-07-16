@extends('layouts.lms')

@section('title', 'Edit Kelas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">✏️ Edit Kelas</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui informasi kelas</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">🎓</div>
                    <h5 class="content-card-title">Form Edit Kelas</h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">🎓 Nama Kelas</label>
                            <input class="form-control" name="name" value="{{ old('name', $class->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">📆 Tingkat (X / XI / XII)</label>
                            <input class="form-control" name="level" value="{{ old('level', $class->level) }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">🧪 Jurusan</label>
                            <select class="form-select" name="major" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="IPA" @selected(old('major', $class->major) === 'IPA')​>IPA</option>
                                <option value="IPS" @selected(old('major', $class->major) === 'IPS')​>IPS</option>
                                <option value="Bahasa" @selected(old('major', $class->major) === 'Bahasa')​>Bahasa</option>
                                <option value="Umum" @selected(old('major', $class->major) === 'Umum')​>Umum</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">📅 Tahun Ajaran</label>
                            <select class="form-select" name="academic_year_id">
                                <option value="">-</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" @selected(old('academic_year_id', $class->academic_year_id) == $year->id)>
                                        {{ $year->name }}{{ $year->is_active ? ' (aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">👨‍🏫 Wali Kelas</label>
                            <select class="form-select" name="homeroom_teacher_id">
                                <option value="">-</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id)>
                                        {{ $teacher->user?->name ?? 'Guru #' . $teacher->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">✓ Update Kelas</button>
                            <a class="btn btn-outline-secondary-theme" href="{{ route('admin.classes.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
