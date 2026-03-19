@extends('layouts.lms')

@section('title', 'Tambah Kelas')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">🎓 Tambah Kelas Baru</h1>
        <p class="text-muted mb-0">Buat kelas baru untuk sistem LMS</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.classes.store') }}">
                        @csrf

                        <!-- Nama Kelas -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Nama Kelas</label>
                            <input class="form-control" style="border-color: #25671E;" name="name" value="{{ old('name') }}" placeholder="Contoh: X A" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📆 Tingkat</label>
                            <select class="form-select" style="border-color: #25671E;" name="level" required>
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
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🧪 Jurusan</label>
                            <select class="form-select" style="border-color: #25671E;" name="major" required>
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
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tahun Ajaran</label>
                            <select class="form-select" style="border-color: #25671E;" name="academic_year_id" required>
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
                            <label class="form-label" style="font-weight: 600; color: #25671E;">👨‍🏫 Wali Kelas</label>
                            <select class="form-select" style="border-color: #25671E;" name="homeroom_teacher_id">
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
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Buat Kelas</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('admin.classes.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

