@extends('layouts.lms')

@section('title', 'Edit Tahun Ajaran')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">✏️ Edit Tahun Ajaran</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui informasi tahun ajaran</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">📅</div>
                    <h5 class="content-card-title">Form Edit Tahun Ajaran</h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.academic-years.update', $year) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">📅 Nama</label>
                            <input class="form-control" name="name" value="{{ old('name', $year->name) }}" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">📅 Tanggal Mulai (Opsional)</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $year->start_date ? $year->start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">📅 Tanggal Selesai (Opsional)</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $year->end_date ? $year->end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active"
                                   @checked(old('is_active', $year->is_active))>
                            <label class="form-check-label fw-medium" for="is_active">✓ Jadikan aktif</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">✓ Update Tahun Ajaran</button>
                            <a class="btn btn-outline-secondary-theme" href="{{ route('admin.academic-years.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
