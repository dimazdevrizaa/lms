@extends('layouts.lms')

@section('title', 'Mata Pelajaran - ' . $class->name)

@section('content')
<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.attendances.index') }}" class="text-decoration-none">Presensi</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">
            📚 Mata Pelajaran — {{ $class->name }}
        </h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Pilih mata pelajaran untuk mengelola presensi pertemuan kelas {{ $class->name }} ({{ $class->students_count }} Siswa).</p>
    </div>
    <a href="{{ route('admin.attendances.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
    </a>
</div>

@if($subjects->isEmpty())
    <div class="content-card text-center p-5">
        <i class="fas fa-book text-muted fa-3x mb-3"></i>
        <h5 class="text-secondary fw-semibold">Belum Ada Mata Pelajaran</h5>
        <p class="text-muted small mb-0">Belum ada mata pelajaran yang dikonfigurasi untuk kelas {{ $class->name }}.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($subjects as $subject)
            <div class="col-md-6 col-lg-4">
                <div class="content-card h-100 transition-hover">
                    <div class="content-card-body p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold text-dark mb-0 fs-6">{{ $subject->name }}</h5>
                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                                    {{ $subject->code ?? 'MAPEL' }}
                                </span>
                            </div>

                            <div class="d-flex gap-3 text-muted small my-3">
                                <div>
                                    <i class="fas fa-calendar-check text-primary me-1"></i>
                                    <strong>{{ $subject->meeting_count }}</strong> Pertemuan
                                </div>
                                <div>
                                    <i class="fas fa-clipboard-check text-success me-1"></i>
                                    <strong>{{ $subject->completed_attendance_count }}</strong> Terisi
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top mt-3">
                            <a href="{{ route('admin.attendances.showSubject', ['class' => $class->id, 'subject' => $subject->id]) }}" class="btn btn-primary btn-sm w-100 rounded-3 fw-semibold">
                                Lihat Pertemuan & Presensi <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
