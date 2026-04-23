@extends('layouts.lms')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📅 Tambah Tahun Ajaran Baru</h1>
        <p class="text-muted mb-0">Buat tahun ajaran baru untuk sistem LMS</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.academic-years.store') }}">
                        @csrf

                        <!-- Nama Tahun Ajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Nama Tahun Ajaran</label>
                            <input class="form-control" style="border-color: #25671E;" name="name" value="{{ old('name') }}" placeholder="Contoh: 2025/2026" required>
                            <small class="text-muted">Format: TAHUN/TAHUN (contoh: 2025/2026)</small>
                            @error('name')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Rentang Waktu -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal Mulai (Opsional)</label>
                                <input type="date" class="form-control" style="border-color: #25671E;" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal Selesai (Opsional)</label>
                                <input type="date" class="form-control" style="border-color: #25671E;" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" @checked(old('is_active')) style="border-color: #25671E;">
                                <label class="form-check-label" for="is_active" style="color: #25671E; font-weight: 500;">
                                    ✓ Jadikan Tahun Ajaran Aktif
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Hanya satu tahun ajaran yang bisa aktif sekaligus</small>
                            @error('is_active')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info border-left-4" style="border-left-color: #25671E; padding: 12px;">
                            <small>
                                <strong>💡 Tips:</strong> Tahun ajaran aktif adalah tahun yang digunakan untuk kegiatan pembelajaran saat ini. Pastikan untuk mengatur tahun ajaran sebelumnya sebagai nonaktif.
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Buat Tahun Ajaran</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('admin.academic-years.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

