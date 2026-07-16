@extends('layouts.lms')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">📅 Tambah Tahun Ajaran Baru</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Buat tahun ajaran baru untuk sistem LMS</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">📅</div>
                    <h5 class="content-card-title">Form Tahun Ajaran</h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.academic-years.store') }}">
                        @csrf

                        <!-- Nama Tahun Ajaran -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">📅 Nama Tahun Ajaran</label>
                            <input class="form-control" name="name" value="{{ old('name') }}" placeholder="Contoh: 2025/2026" required>
                            <small class="text-muted">Format: TAHUN/TAHUN (contoh: 2025/2026)</small>
                            @error('name')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Rentang Waktu -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">📅 Tanggal Mulai (Opsional)</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">📅 Tanggal Selesai (Opsional)</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" @checked(old('is_active'))>
                                <label class="form-check-label fw-medium" for="is_active">
                                    ✓ Jadikan Tahun Ajaran Aktif
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Hanya satu tahun ajaran yang bisa aktif sekaligus</small>
                            @error('is_active')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info" style="border-left: 4px solid var(--primary); border-radius: var(--radius-sm);">
                            <small>
                                <strong>💡 Tips:</strong> Tahun ajaran aktif adalah tahun yang digunakan untuk kegiatan pembelajaran saat ini. Pastikan untuk mengatur tahun ajaran sebelumnya sebagai nonaktif.
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-lg btn-primary" type="submit">✓ Buat Tahun Ajaran</button>
                            <a class="btn btn-lg btn-outline-secondary-theme" href="{{ route('admin.academic-years.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
