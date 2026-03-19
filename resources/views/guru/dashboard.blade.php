@extends('layouts.lms')

@section('title', 'Dashboard Guru')

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-2">👨‍🏫 Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-muted mb-0">Kelola materi dan tugas pembelajaran Anda</p>
        </div>
        <div class="text-muted small">
            {{ date('l, d F Y') }}
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="row mb-5">
        <!-- Materi Upload -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">📚 Materi Upload</h5>
                    <p class="h3 mb-0" style="color: #25671E; font-weight: 700;">{{ $materialsCount ?? 0 }}</p>
                    <small class="text-muted">Materi pembelajaran aktif</small>
                </div>
                <a href="{{ route('guru.meetings.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Tugas Aktif -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">📋 Tugas Aktif</h5>
                    <p class="h3 mb-0" style="color: #48A111; font-weight: 700;">{{ $assignmentsCount ?? 0 }}</p>
                    <small class="text-muted">Total tugas dibuat</small>
                </div>
                <a href="{{ route('guru.meetings.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Pengumpulan Tugas -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">✅ Pengumpulan</h5>
                    <p class="h3 mb-0" style="color: #F2B50B; font-weight: 700;">{{ $submissionsCount ?? 0 }}</p>
                    <small class="text-muted">Total pengumpulan siswa</small>
                </div>
                <a href="{{ route('guru.meetings.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Koreksi Pending -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">⏳ Pending Koreksi</h5>
                    <p class="h3 mb-0" style="color: #25671E; font-weight: 700;">{{ $pendingGradesCount ?? 0 }}</p>
                    <small class="text-muted">Tugas menunggu penilaian</small>
                </div>
                <a href="{{ route('guru.meetings.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Kelas & Mata Pelajaran -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">🎓 Kelas yang Diampu (Per Jurusan)</h5>
                    <div class="list-group list-group-flush">
                        @forelse($assignedClasses as $major => $majorClasses)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2 text-primary text-uppercase small" style="letter-spacing: 1px;">
                                    <i class="fas fa-microscope me-1"></i> JURUSAN {{ $major ?? 'UMUM' }}
                                </h6>
                                @foreach($majorClasses as $class)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded-circle bg-light me-3">
                                                <i class="fas fa-users text-muted small"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $class->name }}</h6>
                                                <small class="text-muted">{{ $class->students_count }} siswa</small>
                                            </div>
                                        </div>
                                        <a href="{{ route('guru.meetings.index', ['class_id' => $class->id]) }}" class="btn btn-sm btn-outline-success rounded-pill">Buka Ruang</a>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="py-4 text-center text-muted">
                                <p>Belum ada kelas yang diampu.</p>
                                <small>Gunakan menu Wali Kelas jika Anda ditunjuk sebagai Wali Kelas.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="col-md-5 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">⚡ Aksi Cepat</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @if(auth()->user()->teacher && auth()->user()->teacher->homeroomClasses()->count() > 0)
                            <a href="{{ route('guru.classroom.index') }}" class="btn btn-sm btn-outline-info">📚 Kelola Kelas (Wali Kelas)</a>
                        @endif
                        <a href="{{ route('guru.meetings.index') }}" class="btn btn-sm btn-outline-primary">📚 Ruang Kelas (Materi & Tugas)</a>
                        <a href="{{ route('guru.attendances.index') }}" class="btn btn-sm btn-outline-warning">✅ Catat Kehadiran</a>
                    </div>
                </div>
            </div>

            <!-- Notifikasi Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">🔔 Notifikasi</h5>
                    <div class="small">
                        <div class="mb-3 pb-3 border-bottom">
                            <span class="badge" style="background-color: #48A111;">Baru</span>
                            <p class="mb-0 mt-2">5 siswa belum mengumpulkan tugas</p>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <span class="badge" style="background-color: #F2B50B;">Info</span>
                            <p class="mb-0 mt-2">Jadwal penilaian UTS 10 Maret</p>
                        </div>
                        <div>
                            <span class="badge" style="background-color: #25671E;">Reminder</span>
                            <p class="mb-0 mt-2">Masukan nilai untuk tugas minggu lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

