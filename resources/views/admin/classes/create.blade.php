@extends('layouts.lms')

@section('title', 'Tambah Kelas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">🎓 Tambah Kelas Baru</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Buat kelas baru untuk sistem LMS</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">🎓</div>
                    <h5 class="content-card-title">Form Tambah Kelas</h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.classes.store') }}">
                        @csrf

                        <!-- Nama Kelas -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">🎓 Nama Kelas</label>
                            <input class="form-control" name="name" value="{{ old('name') }}" placeholder="Contoh: X A" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">📆 Tingkat</label>
                            <select class="form-select" name="level" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X" @selected(old('level') === 'X')​>🎓 Kelas X (Sepuluh)</option>
                                <option value="XI" @selected(old('level') === 'XI')​>🎓 Kelas XI (Sebelas)</option>
                                <option value="XII" @selected(old('level') === 'XII')​>🎓 Kelas XII (Dua Belas)</option>
                            </select>
                            @error('level')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                         <!-- Jurusan -->
                         <div class="mb-4">
                            <label class="form-label fw-semibold">🧪 Jurusan</label>
                            <select class="form-select" name="major" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="IPA" @selected(old('major') === 'IPA')​>🧪 IPA (Sains)</option>
                                <option value="IPS" @selected(old('major') === 'IPS')​>🧪 IPS (Sosial)</option>
                                <option value="Bahasa" @selected(old('major') === 'Bahasa')​>📖 Bahasa</option>
                                <option value="Umum" @selected(old('major') === 'Umum')​>📂 Umum</option>
                            </select>
                            @error('major')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tahun Ajaran -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">📅 Tahun Ajaran</label>
                            <select class="form-select" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" @selected(old('academic_year_id') == $year->id)>
                                        {{ $year->name }}{{ $year->is_active ? ' (✓ Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Wali Kelas -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">👨‍🏫 Wali Kelas</label>
                            <select class="form-select" name="homeroom_teacher_id">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id') == $teacher->id)>
                                        {{ $teacher->user?->name ?? 'Guru #' . $teacher->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('homeroom_teacher_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-lg btn-primary" type="submit">✓ Buat Kelas</button>
                            <a class="btn btn-lg btn-outline-secondary-theme" href="{{ route('admin.classes.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
