@extends('layouts.lms')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">➕ Tambah Mata Pelajaran Baru</h1>
        <p class="text-muted mb-0">Daftarkan mata pelajaran baru ke dalam sistem</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tatausaha.subjects.store') }}">
                        @csrf

                        <!-- Nama Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Nama Mata Pelajaran</label>
                            <input class="form-control" style="border-color: #25671E;" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Kode Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🔖 Kode Mata Pelajaran</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: MTK">
                            @error('code')
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
                                <option value="Umum" @selected(old('major') === 'Umum')​>📂 Umum (Semua Jurusan)</option>
                            </select>
                            @error('major')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Guru (Pengampu) -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">👨‍🏫 Guru (Opsional)</label>
                            <select class="form-select" style="border-color: #25671E;" name="teacher_id">
                                <option value="">-- Belum ditentukan --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>
                                        {{ $teacher->user->name }} (NIP: {{ $teacher->nip ?? '—' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Simpan Mata Pelajaran</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('tatausaha.subjects.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-5">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Panduan</h5>
                    <ul class="small text-muted">
                        <li class="mb-3">
                            <strong style="color: #25671E;">Nama Mata Pelajaran</strong><br>
                            Masukkan nama mata pelajaran dengan jelas (wajib)
                        </li>
                        <li class="mb-3">
                            <strong style="color: #48A111;">Kode</strong><br>
                            Singkat untuk mata pelajaran (opsional)
                        </li>
                        <li class="mb-3">
                            <strong style="color: #F2B50B;">Guru Pengampu</strong><br>
                            Pilih guru yang mengajar mata pelajaran ini (opsional)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
