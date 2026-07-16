@extends('layouts.lms')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">➕ Tambah Mata Pelajaran Baru</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Daftarkan mata pelajaran baru ke dalam sistem</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">📖</div>
                    <h5 class="content-card-title">Form Mata Pelajaran</h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.subjects.store') }}">
                        @csrf

                        <!-- Nama Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">📖 Nama Mata Pelajaran</label>
                            <input class="form-control" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Kode Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">🔖 Kode Mata Pelajaran</label>
                            <input class="form-control" type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: MTK">
                            @error('code')
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
                                <option value="Umum" @selected(old('major') === 'Umum')​>📂 Umum (Semua Jurusan)</option>
                            </select>
                            @error('major')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-lg btn-primary" type="submit">✓ Simpan Mata Pelajaran</button>
                            <a class="btn btn-lg btn-outline-secondary-theme" href="{{ route('admin.subjects.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-5">
            <div class="content-card" style="border-top: 4px solid var(--accent);">
                <div class="content-card-header">
                    <div class="content-card-header-icon" style="background: linear-gradient(135deg, rgba(249, 168, 37, 0.15), rgba(249, 168, 37, 0.06)); color: var(--accent);">💡</div>
                    <h5 class="content-card-title">Panduan</h5>
                </div>
                <div class="content-card-body">
                    <ul class="small text-muted mb-0" style="padding-left: 1.2rem;">
                        <li class="mb-3">
                            <strong style="color: var(--primary);">Nama Mata Pelajaran</strong><br>
                            Masukkan nama mata pelajaran dengan jelas (wajib)
                        </li>
                        <li class="mb-3">
                            <strong style="color: var(--secondary);">Kode</strong><br>
                            Singkat untuk mata pelajaran (opsional)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
