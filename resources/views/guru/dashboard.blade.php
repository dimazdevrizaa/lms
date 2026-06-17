@extends('layouts.lms')

@section('title', 'Dashboard Guru')

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-2">👨‍🏫 Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-muted mb-0">Berikut ringkasan kegiatan mengajar Anda</p>
        </div>
        <div class="text-muted small">
            {{ date('l, d F Y') }}
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="row mb-5">
        <!-- Pertemuan -->
        <div class="col-md-3 col-6 mb-4">
            <div class="card h-100 position-relative shadow-sm" style="border-top: 4px solid #25671E !important;">
                <div class="card-body text-center py-4">
                    <div class="mb-2"><i class="fas fa-calendar-alt fa-2x" style="color: #25671E; opacity: 0.6;"></i></div>
                    <p class="display-5 fw-bold mb-1" style="color: #25671E;">{{ $meetingsCount ?? 0 }}</p>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Pertemuan</small>
                </div>
                <a href="{{ route('guru.meetings.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Materi Upload -->
        <div class="col-md-3 col-6 mb-4">
            <div class="card h-100 position-relative shadow-sm" style="border-top: 4px solid #48A111 !important;">
                <div class="card-body text-center py-4">
                    <div class="mb-2"><i class="fas fa-book fa-2x" style="color: #48A111; opacity: 0.6;"></i></div>
                    <p class="display-5 fw-bold mb-1" style="color: #48A111;">{{ $materialsCount ?? 0 }}</p>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Materi Ajar</small>
                </div>
                <a href="{{ route('guru.materials.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Tugas Aktif -->
        <div class="col-md-3 col-6 mb-4">
            <div class="card h-100 position-relative shadow-sm" style="border-top: 4px solid #F2B50B !important;">
                <div class="card-body text-center py-4">
                    <div class="mb-2"><i class="fas fa-file-alt fa-2x" style="color: #F2B50B; opacity: 0.6;"></i></div>
                    <p class="display-5 fw-bold mb-1" style="color: #F2B50B;">{{ $assignmentsCount ?? 0 }}</p>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Tugas & Latihan</small>
                </div>
                <a href="{{ route('guru.assignments.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Pending Koreksi -->
        <div class="col-md-3 col-6 mb-4">
            <div class="card h-100 position-relative shadow-sm" style="border-top: 4px solid {{ $pendingGradesCount > 0 ? '#dc3545' : '#48A111' }} !important;">
                <div class="card-body text-center py-4">
                    <div class="mb-2"><i class="fas fa-check-double fa-2x" style="color: {{ $pendingGradesCount > 0 ? '#dc3545' : '#48A111' }}; opacity: 0.6;"></i></div>
                    <p class="display-5 fw-bold mb-1" style="color: {{ $pendingGradesCount > 0 ? '#dc3545' : '#48A111' }};">{{ $pendingGradesCount ?? 0 }}</p>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Belum Dinilai</small>
                </div>
                <a href="{{ route('guru.assignments.grading', ['filter' => 'pending']) }}" class="stretched-link"></a>
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
                                <small>Hubungi Tata Usaha untuk pendataan penugasan mengajar.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions + Tugas Perlu Dinilai -->
        <div class="col-md-5 mb-4">
            <!-- Aksi Cepat -->
            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">⚡ Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('guru.meetings.create') }}" class="btn btn-outline-primary text-start py-3 px-4" style="border-radius: 12px;">
                            <i class="fas fa-calendar-plus me-2"></i> Buat Pertemuan Baru
                        </a>
                        <a href="{{ route('guru.materials.create') }}" class="btn btn-outline-success text-start py-3 px-4" style="border-radius: 12px;">
                            <i class="fas fa-upload me-2"></i> Upload Materi Ajar
                        </a>
                        <a href="{{ route('guru.assignments.create') }}" class="btn btn-outline-warning text-start py-3 px-4" style="border-radius: 12px;">
                            <i class="fas fa-plus-circle me-2"></i> Buat Tugas / Latihan
                        </a>
                        @if($pendingGradesCount > 0)
                            <a href="{{ route('guru.assignments.grading', ['filter' => 'pending']) }}" class="btn btn-danger text-start py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-check-double me-2"></i> Nilai Tugas Siswa
                                <span class="badge bg-white text-danger ms-2">{{ $pendingGradesCount }} menunggu</span>
                            </a>
                        @else
                            <a href="{{ route('guru.assignments.grading') }}" class="btn btn-outline-secondary text-start py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-check-double me-2"></i> Lihat Penilaian
                            </a>
                        @endif
                        <a href="{{ route('guru.attendances.index') }}" class="btn btn-outline-info text-start py-3 px-4" style="border-radius: 12px;">
                            <i class="fas fa-clipboard-check me-2"></i> Catat Kehadiran Siswa
                        </a>
                        @if(auth()->user()->teacher && auth()->user()->teacher->homeroomClasses()->count() > 0)
                            <a href="{{ route('guru.classroom.index') }}" class="btn btn-outline-dark text-start py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-users me-2"></i> Kelola Kelas Perwalian
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tugas Terbaru yang Perlu Dinilai -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title fw-bold mb-0">📬 Perlu Dinilai</h5>
                        @if($pendingGradesCount > 0)
                            <a href="{{ route('guru.assignments.grading', ['filter' => 'pending']) }}" class="small text-decoration-none" style="color: #48A111;">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                    @if($recentPendingAssignments->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x mb-3" style="color: #48A111; opacity: 0.3;"></i>
                            <p class="text-muted mb-0">Semua tugas sudah dinilai! 🎉</p>
                        </div>
                    @else
                        @foreach($recentPendingAssignments as $assignment)
                            @php
                                $ungradedCount = $assignment->submissions->whereNull('score')->count();
                            @endphp
                            <a href="{{ route('guru.assignments.show', $assignment) }}" class="d-block text-decoration-none mb-3 p-3 rounded-3 border" style="transition: all 0.2s; background: #fafafa;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold" style="color: #25671E; font-size: 0.9rem;">{{ $assignment->title }}</h6>
                                        <div class="d-flex gap-2 small text-muted">
                                            <span>{{ $assignment->schoolClass?->name ?? '-' }}</span>
                                            <span>•</span>
                                            <span>{{ $assignment->subject?->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.7rem;">{{ $ungradedCount }} belum dinilai</span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
