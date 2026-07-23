@extends('layouts.lms')

@section('title', 'Kelola Presensi Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">
            📋 Kelola Presensi Siswa (Admin)
        </h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Pilih kelas di bawah ini untuk melihat dan mengelola presensi per mata pelajaran.</p>
    </div>
</div>

@if($classes->isEmpty())
    <div class="content-card text-center p-5">
        <i class="fas fa-school text-muted fa-3x mb-3"></i>
        <h5 class="text-secondary fw-semibold">Belum Ada Data Kelas</h5>
        <p class="text-muted small mb-0">Silakan tambahkan data kelas terlebih dahulu di menu Data Kelas.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($classes as $class)
            <div class="col-md-6 col-lg-4">
                <div class="content-card h-100 transition-hover" style="border-left: 5px solid var(--primary, #0d6efd) !important;">
                    <div class="content-card-body p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold text-dark mb-0">{{ $class->name }}</h5>
                                <span class="badge bg-light text-primary border rounded-pill px-3 py-1 font-monospace small">
                                    {{ $class->slug }}
                                </span>
                            </div>

                            <div class="d-flex gap-3 text-muted small my-3">
                                <div>
                                    <i class="fas fa-user-graduate text-primary me-1"></i>
                                    <strong>{{ $class->students_count }}</strong> Siswa
                                </div>
                                <div>
                                    <i class="fas fa-calendar-alt text-success me-1"></i>
                                    <strong>{{ $class->meetings_count }}</strong> Pertemuan
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top mt-3">
                            <a href="{{ route('admin.attendances.showClass', $class->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-semibold">
                                Pilih Kelas <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
