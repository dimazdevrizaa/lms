@extends('layouts.lms')

@section('title', 'Dashboard Tata Usaha')

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-2">📋 Dashboard Tata Usaha</h1>
            <p class="text-muted mb-0">Kelola administrasi sekolah</p>
        </div>
        <div class="text-muted small">
            {{ date('l, d F Y') }}
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="row mb-5">
        <!-- Total Siswa -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">👥 Total Siswa</h5>
                            <p class="display-5" style="color: #25671E; font-weight: 700; margin-bottom: 0;">{{ $studentCount ?? 0 }}</p>
                            <small class="text-muted">Siswa terdaftar</small>
                        </div>
                    </div>
                </div>
                <a href="{{ route('tatausaha.students.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">🎓 Total Guru</h5>
                            <p class="display-5" style="color: #48A111; font-weight: 700; margin-bottom: 0;">{{ $teacherCount ?? 0 }}</p>
                            <small class="text-muted">Guru aktif</small>
                        </div>
                    </div>
                </div>
                <a href="{{ route('tatausaha.teachers.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">🏫 Total Kelas</h5>
                            <p class="display-5" style="color: #F2B50B; font-weight: 700; margin-bottom: 0;">{{ $classCount ?? 0 }}</p>
                            <small class="text-muted">Kelas tersedia</small>
                        </div>
                    </div>
                </div>
                <a href="{{ route('tatausaha.reports.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Tahun Ajaran -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">📅 Tahun Ajaran</h5>
                            <p class="display-5" style="color: #F7F0F0; font-weight: 700; text-shadow: 1px 1px 2px #25671E; margin-bottom: 0;">
                                {{ $academicYear ? $academicYear->name : 'N/A' }}
                            </p>
                            <small class="text-muted">Aktif</small>
                        </div>
                    </div>
                </div>
                {{-- Hanya link jika ada akses ke akademik, untuk TU sebagai placeholder --}}
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Data Siswa & Guru -->
        <div class="col-md-7 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">📊 Statistik Kelas (Per Jurusan)</h5>
                    <div class="list-group list-group-flush">
                        @foreach($classes as $major => $majorClasses)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2 text-primary text-uppercase small">
                                    <i class="fas fa-layer-group me-1"></i> JURUSAN {{ $major ?? 'UMUM' }}
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kelas</th>
                                                <th class="text-center">Siswa</th>
                                                <th>Wali Kelas</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($majorClasses as $class)
                                                <tr>
                                                    <td><strong>{{ $class->name }}</strong></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $class->students_count }}</span>
                                                    </td>
                                                    <td>
                                                        <small>{{ $class->homeroomTeacher?->user?->name ?? 'Belum ada' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('tatausaha.students.index', ['class_id' => $class->id]) }}" class="btn btn-sm btn-link p-0 text-success">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kegiatan Terbaru -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">📝 Kegiatan Terbaru</h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>Pendaftaran Siswa Baru</strong>
                                <small class="text-muted">2 March 2026</small>
                            </div>
                            <small class="text-muted">5 siswa baru terdaftar</small>
                        </div>
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>Pembaruan Data Guru</strong>
                                <small class="text-muted">1 March 2026</small>
                            </div>
                            <small class="text-muted">2 guru melakukan update profil</small>
                        </div>
                        <div class="list-group-item px-0 pt-3">
                            <div class="d-flex justify-content-between">
                                <strong>Pengaturan Jadwal</strong>
                                <small class="text-muted">28 Feb 2026</small>
                            </div>
                            <small class="text-muted">Jadwal semester 2 telah diperbarui</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="col-md-5 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">⚡ Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('tatausaha.students.index') }}" class="btn btn-outline-primary">
                            👥 Kelola Siswa
                        </a>
                        <a href="{{ route('tatausaha.teachers.index') }}" class="btn btn-outline-success">
                            🎓 Kelola Guru
                        </a>
                        <a href="{{ route('tatausaha.reports.index') }}" class="btn btn-outline-warning">
                            📊 Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reminder/Alert Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">⚠️ Pengingat</h5>
                    <div class="small">
                        <div class="alert alert-warning border-top-4" style="border-top-color: #F2B50B; padding: 12px; margin-bottom: 12px;">
                            <strong>📅 UTS Minggu Depan</strong>
                            <p class="mb-0 mt-2">Persiapkan jadwal dan ruang ujian</p>
                        </div>
                        <div class="alert alert-info border-top-4" style="border-top-color: #25671E; padding: 12px; margin-bottom: 12px;">
                            <strong>📝 Input Nilai</strong>
                            <p class="mb-0 mt-2">Guru diminta untuk melengkapi input nilai</p>
                        </div>
                        <div class="alert alert-success border-top-4" style="border-top-color: #48A111; padding: 12px;">
                            <strong>✅ Pembayaran SPP</strong>
                            <p class="mb-0 mt-2">Pengingat: Deadline SPP 10 Maret 2026</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

