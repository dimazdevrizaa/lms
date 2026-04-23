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
        <!-- Jadwal Hari Ini & Kelas -->
        <div class="col-md-7 mb-4">
            <!-- Jadwal Mengajar Hari Ini -->
            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header border-bottom-0 pt-4 pb-0 bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">📅 Jadwal Mengajar Hari Ini</h5>
                        <span class="badge" style="background-color: #25671E;">{{ $todayIndo }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($todaySchedules->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($todaySchedules as $schedule)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded bg-light me-3 text-center" style="min-width: 65px;">
                                            <div class="small fw-bold" style="color: #48A111;">{{ \Carbon\Carbon::parse($schedule->timeSlot->start_time)->format('H:i') }}</div>
                                            <div class="small text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($schedule->timeSlot->end_time)->format('H:i') }}</div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ $schedule->subject->name ?? '-' }}</h6>
                                            <div class="d-flex align-items-center gap-2 small text-muted">
                                                <span><i class="fas fa-door-open me-1"></i> Kelas {{ $schedule->schoolClass->name ?? '-' }}</span>
                                                <span style="color: #ccc;">|</span>
                                                <span><i class="fas fa-clock me-1"></i> {{ $schedule->timeSlot->label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('guru.meetings.create', ['class_id' => $schedule->class_id, 'subject_id' => $schedule->subject_id]) }}" class="btn btn-sm shadow-sm" style="background-color: #F2B50B; color: #fff; border-radius: 8px;">
                                        <i class="fas fa-plus me-1"></i> Buat Pertemuan
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-mug-hot fa-3x" style="color: #e0e0e0;"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: #666;">Tidak Ada Jadwal Mengajar</h6>
                            <p class="small text-muted mb-0">Anda tidak memiliki jadwal kelas pada hari {{ $todayIndo }} berdasarkan jadwal TU.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kelas & Mata Pelajaran -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">🎓 Daftar Kelas Diampu</h5>
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

