@extends('layouts.lms')

@section('title', 'Detail Kelas - ' . $class->name)

@section('content')
<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}" class="text-decoration-none">Kelola Kelas</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
            </ol>
        </nav>
        <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">
            🎓 Kelola Kelas — {{ $class->name }}
        </h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">
            Tingkat <strong>{{ $class->level ?? '-' }}</strong> · 
            Jurusan <strong>{{ $class->major ?? '-' }}</strong> · 
            Wali Kelas: <strong>{{ $class->homeroomTeacher?->user?->name ?? 'Belum ditentukan' }}</strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-primary">
            ✏️ Edit Kelas
        </a>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>
</div>

<!-- Overview Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="content-card h-100">
            <div class="content-card-body p-3 d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-user-graduate fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Siswa</span>
                    <strong class="fs-5 text-dark">{{ $class->students_count }} Siswa</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card h-100">
            <div class="content-card-body p-3 d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-book fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Mata Pelajaran</span>
                    <strong class="fs-5 text-dark">{{ $subjects->count() }} Mapel</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card h-100">
            <div class="content-card-body p-3 d-flex align-items-center">
                <div class="rounded-circle bg-info-subtle text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-chalkboard-teacher fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Wali Kelas</span>
                    <strong class="fs-6 text-dark text-truncate d-block" style="max-width: 140px;" title="{{ $class->homeroomTeacher?->user?->name ?? '-' }}">
                        {{ $class->homeroomTeacher?->user?->name ?? 'Belum Ditentukan' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card h-100">
            <div class="content-card-body p-3 d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-calendar fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Tahun Ajaran</span>
                    <strong class="fs-6 text-dark">{{ $class->academicYear?->name ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 1: Kelola Presensi per Mata Pelajaran -->
<div class="content-card mb-4">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="content-card-header-icon me-2">📋</div>
            <h5 class="content-card-title mb-0">Kelola Presensi Siswa per Mata Pelajaran</h5>
        </div>
        <span class="badge bg-light text-muted border px-3 py-1 font-monospace">
            Kelas: {{ $class->name }}
        </span>
    </div>
    <div class="content-card-body pt-3">
        @if($subjects->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fas fa-book-open fa-2x mb-2 opacity-50"></i>
                <p class="mb-0">Belum ada mata pelajaran untuk kelas ini.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($subjects as $subject)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border border-light-subtle shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0 fs-6">{{ $subject->name }}</h6>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                                            {{ $subject->code ?? 'MAPEL' }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-3 text-muted small my-2">
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
                                <div class="pt-2 border-top mt-2">
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
    </div>
</div>

<!-- Section 2: Daftar Siswa Kelas -->
<div class="content-card">
    <div class="content-card-header">
        <div class="content-card-header-icon">👥</div>
        <h5 class="content-card-title">Daftar Siswa Kelas ({{ $students->count() }} Siswa)</h5>
    </div>
    <div class="content-card-body pt-2">
        @if($students->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fas fa-user-graduate fa-2x mb-2 opacity-50"></i>
                <p class="mb-0">Belum ada siswa yang terdaftar di kelas ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-2" style="width: 50px;">#</th>
                            <th class="py-2">Nama Siswa</th>
                            <th class="py-2">NISN</th>
                            <th class="py-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr>
                                <td class="ps-3 text-muted small fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <strong class="text-dark">{{ $student->user->name ?? '-' }}</strong>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted small">{{ $student->nisn ?? '-' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $student->user->email ?? '-' }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
