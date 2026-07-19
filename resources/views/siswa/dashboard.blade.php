@extends('layouts.lms')

@section('title', 'Dashboard Siswa')

@section('content')
    <!-- Header Banner -->
    <div class="page-header-banner reveal">
        <div class="page-header-banner-inner">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"> Selamat Datang, {{ Auth::user()->name }}</h1>
                    <p>Pantau progres belajar Anda di sini</p>
                </div>
                <div class="text-end">
                    <div class="small" style="color: rgba(255,255,255,0.7);">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    @if(Auth::user()->student && Auth::user()->student->schoolClass)
                        <div class="mt-2 d-flex gap-2 justify-content-end">
                            <span class="badge">
                                {{ Auth::user()->student->schoolClass->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Pelajaran Hari Ini -->
    <div class="content-card mb-4 reveal reveal-delay-1" style="border-radius: var(--radius-md) !important;">
        <div class="content-card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="content-card-header-icon" style="background: linear-gradient(135deg, rgba(37, 103, 30, 0.1), rgba(37, 103, 30, 0.05)); color: var(--primary);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h5 class="content-card-title mb-0">Jadwal Pelajaran Hari Ini</h5>
            </div>
            <span class="badge bg-light text-muted border" style="font-size: 0.75rem;">
                Hari {{ $todayDayName }}
            </span>
        </div>
        <div class="content-card-body p-4">
            @if($todaySchedules->isNotEmpty())
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    @foreach($todaySchedules as $schedule)
                        <div class="col">
                            <div class="p-3 border rounded-3 h-100 d-flex justify-content-between align-items-center bg-light-subtle">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-white border text-center p-2 rounded-2" style="min-width: 90px; height: 60px;">
                                        <small class="text-success fw-bold" style="font-size: 0.7rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ substr($schedule->timeSlot->start_time, 0, 5) }}</small>
                                        <div style="width: 15px; height: 1px; background-color: #dee2e6; margin: 2px 0;"></div>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ substr($schedule->timeSlot->end_time, 0, 5) }}</small>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $schedule->subject->name ?? $schedule->activity ?? '-' }}</span>
                                        @if($schedule->subject)
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="far fa-user me-1"></i> {{ $schedule->teacher->user->name ?? 'Tidak ada guru' }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success-theme rounded-pill" style="font-size: 0.65rem;">
                                    {{ $schedule->timeSlot->label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <div class="text-muted mb-2" style="font-size: 2.5rem;"><i class="far fa-calendar-times"></i></div>
                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">Tidak ada jadwal pelajaran hari ini (Hari Libur).</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="stats-grid reveal reveal-delay-2">
        <!-- Tugas Disubmit -->
        <div class="stat-card stat-card--behavior">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Tugas Disubmit</div>
                    <div class="stat-value stat-value--green">{{ $submissionCount ?? 0 }}</div>
                    <div class="stat-sub">Tugas dikumpulkan</div>
                </div>
                <div class="stat-icon-circle stat-icon-circle--green">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <a href="{{ route('siswa.assignments.index') }}" class="stretched-link"></a>
        </div>

        <!-- Materi Tersedia -->
        <div class="stat-card stat-card--attendance">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Materi Pembelajaran</div>
                    <div class="stat-value stat-value--primary">{{ $materialsCount ?? 0 }}</div>
                    <div class="stat-sub">Materi tersedia</div>
                </div>
                <div class="stat-icon-circle stat-icon-circle--deep">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <a href="{{ route('siswa.subjects.index') }}" class="stretched-link"></a>
        </div>

        <!-- Kehadiran -->
        <div class="stat-card stat-card--grades">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Kehadiran Harian</div>
                    <div class="stat-value stat-value--green">{{ $attendanceRate }}%</div>
                    <div class="stat-sub">Kehadiran bulan ini</div>
                </div>
                <div class="stat-icon-circle stat-icon-circle--gold">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <a href="{{ route('siswa.attendance.index') }}" class="stretched-link"></a>
        </div>

        <!-- Nilai Rata-rata -->
        <div class="stat-card stat-card--grades">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Rata-Rata Nilai</div>
                    <div class="stat-value stat-value--primary">{{ $averageGrade }}</div>
                    <div class="stat-sub">Nilai tugas aktif</div>
                </div>
                <div class="stat-icon-circle stat-icon-circle--gold">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <a href="{{ route('siswa.assignments.index') }}" class="stretched-link"></a>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Tugas Terbaru Section -->
        <div class="col-md-7 mb-4 reveal reveal-delay-3">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-header-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5 class="content-card-title">Tugas Terbaru</h5>
                </div>
                <div class="content-card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentAssignments as $assignment)
                            <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3 border-bottom">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--text-heading);">{{ $assignment->subject ? $assignment->subject->name : 'N/A' }} - {{ $assignment->title }}</h6>
                                    <small class="text-muted"><i class="fas fa-clock me-1"></i> Deadline: {{ $assignment->due_at }}</small>
                                </div>
                                <span class="status-badge status-badge--aktif">Aktif</span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="empty-state-text">Tidak ada tugas terbaru.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="col-md-5 mb-4 reveal reveal-delay-3">
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <div class="content-card-header-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="content-card-title">Aksi Cepat</h5>
                </div>
                <div class="content-card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-primary-theme w-100">
                            <i class="fas fa-file-alt"></i> Lihat Semua Tugas
                        </a>
                        <a href="{{ route('siswa.subjects.index') }}" class="btn btn-outline-secondary-theme w-100">
                            <i class="fas fa-book"></i> Lihat Mata Pelajaran
                        </a>
                        <a href="{{ route('siswa.attendance.index') }}" class="btn btn-outline-accent-theme w-100">
                            <i class="fas fa-clipboard-user"></i> Lihat Kehadiran
                        </a>
                    </div>
                </div>
            </div>

            @if(Auth::user()->student)
                <!-- Parent Access Code Card -->
                <div class="content-card mb-4" style="background: linear-gradient(135deg, var(--accent-soft), #FFFBE2) !important; border: 1px solid rgba(249, 168, 37, 0.15) !important;">
                    <div class="content-card-body pt-4">
                        <h6 class="fw-bold mb-2" style="color: #B26A00;"><i class="fas fa-user-shield me-2"></i> Akses Orang Tua</h6>
                        <p class="small text-muted mb-3">Kode akses tidak ditampilkan di dashboard siswa. Jika perlu reset atau pembagian ulang, hubungi wali kelas atau tata usaha.</p>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <code class="bg-white border px-3 py-2 rounded text-dark fw-bold fs-6 flex-grow-1 text-center" style="letter-spacing: 2px; border-color: rgba(249, 168, 37, 0.2) !important;">REDACTED</code>
                            <button class="btn btn-outline-secondary btn-sm px-3 py-2" type="button" disabled aria-disabled="true">
                                Terkelola
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


