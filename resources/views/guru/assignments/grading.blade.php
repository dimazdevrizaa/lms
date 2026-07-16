@extends('layouts.lms')

@section('title', 'Penilaian Tugas')

@section('content')
    <!-- Header -->
    <div class="mb-4">
        <h1 class="mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--text-heading);">📝 Penilaian Tugas</h1>
        <p class="mb-0" style="color: var(--text-muted);">Lihat dan nilai tugas yang telah dikumpulkan siswa</p>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card--behavior">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--deep"><i class="fas fa-file-alt"></i></div>
                <div class="stat-label">Total Tugas</div>
            </div>
            <div class="stat-value stat-value--primary">{{ $totalAssignments }}</div>
        </div>
        <div class="stat-card stat-card--attendance">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--green"><i class="fas fa-inbox"></i></div>
                <div class="stat-label">Total Dikumpulkan</div>
            </div>
            <div class="stat-value stat-value--green">{{ $totalSubmissions }}</div>
        </div>
        <div class="stat-card stat-card--grades">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--gold"><i class="fas fa-clock"></i></div>
                <div class="stat-label">Belum Dinilai</div>
            </div>
            <div class="stat-value" style="color: var(--accent);">{{ $pendingGrading }}</div>
        </div>
        <div class="stat-card stat-card--attendance">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-label">Sudah Dinilai</div>
            </div>
            <div class="stat-value stat-value--green">{{ $gradedSubmissions }}</div>
        </div>
    </div>

    <!-- Filter Kelas -->
    @if(isset($teacherClasses) && $teacherClasses->count() > 0)
        <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
            <span class="small fw-bold me-1" style="color: var(--text-muted);"><i class="fas fa-filter me-1"></i>Kelas:</span>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->except('class_id'), [])) }}"
               class="btn btn-sm {{ !$selectedClassId ? 'text-white' : 'btn-outline-secondary' }}"
               style="{{ !$selectedClassId ? 'background-color: var(--primary); border-color: var(--primary);' : '' }} border-radius: var(--radius-sm);">
                Semua Kelas
            </a>
            @foreach($teacherClasses as $cls)
                <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['class_id' => $cls->id])) }}"
                   class="btn btn-sm {{ $selectedClassId == $cls->id ? 'text-white' : 'btn-outline-secondary' }}"
                   style="{{ $selectedClassId == $cls->id ? 'background-color: var(--primary); border-color: var(--primary);' : '' }} border-radius: var(--radius-sm);">
                    {{ $cls->name }}
                </a>
            @endforeach
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="content-card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-clipboard-check"></i></div>
                <div class="empty-state-text">
                    <strong>Belum ada tugas</strong><br>
                    Tidak ada tugas pada filter kelas ini atau Anda belum membuat tugas apapun.
                </div>
            </div>
        </div>
    @else
        <!-- Filter Tabs -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => null])) }}"
               class="btn btn-sm {{ !request('filter') ? '' : 'btn-outline-secondary' }}"
               style="{{ !request('filter') ? 'background-color: var(--primary); border-color: var(--primary); color: white;' : '' }} border-radius: var(--radius-sm);">
                Semua ({{ $totalAssignments }})
            </a>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => 'pending'])) }}"
               class="btn btn-sm {{ request('filter') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}" style="border-radius: var(--radius-sm);">
                <i class="fas fa-clock me-1"></i> Perlu Dinilai ({{ $pendingGrading > 0 ? $pendingGrading : 0 }})
            </a>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => 'graded'])) }}"
               class="btn btn-sm {{ request('filter') === 'graded' ? 'btn-success' : 'btn-outline-success' }}" style="border-radius: var(--radius-sm);">
                <i class="fas fa-check me-1"></i> Sudah Dinilai
            </a>
        </div>

        <!-- Assignments List -->
        <div class="row">
            @foreach($assignments as $a)
                @php
                    $submissionCount = $a->submissions->count();
                    $ungradedCount = $a->submissions->whereNull('score')->count();
                    $gradedCount = $submissionCount - $ungradedCount;
                    $avgScore = $a->submissions->whereNotNull('score')->avg('score');
                @endphp
                <div class="col-lg-6 mb-4">
                    <div class="content-card h-100" style="cursor: pointer; border-left: 4px solid {{ $ungradedCount > 0 ? 'var(--accent)' : 'var(--secondary)' }};"
                         onclick="window.location='{{ route('guru.assignments.show', $a) }}'">

                        <div class="content-card-body">
                            <!-- Title & Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="color: var(--primary); font-family: 'Plus Jakarta Sans', sans-serif;">{{ $a->title }}</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="status-badge status-badge--hadir" style="font-size: 0.65rem;">{{ $a->schoolClass?->name ?? '-' }}</span>
                                        <span class="status-badge" style="background: rgba(108,117,125,0.1); color: #6c757d; font-size: 0.65rem;">{{ $a->subject?->name ?? '-' }}</span>
                                        @if($a->isOnline())
                                            <span class="status-badge" style="background: rgba(13,110,253,0.1); color: #0d6efd; font-size: 0.65rem;"><i class="fas fa-laptop me-1"></i>Online</span>
                                        @else
                                            <span class="status-badge" style="background: rgba(220,53,69,0.1); color: #dc3545; font-size: 0.65rem;"><i class="fas fa-file-pdf me-1"></i>PDF</span>
                                        @endif
                                    </div>
                                </div>
                                @if($ungradedCount > 0)
                                    <span class="status-badge" style="background: rgba(249,168,37,0.15); color: #B26A00; font-size: 0.75rem;">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $ungradedCount }} belum dinilai
                                    </span>
                                @else
                                    @if($submissionCount > 0)
                                        <span class="status-badge status-badge--hadir" style="font-size: 0.75rem;">
                                            <i class="fas fa-check-circle me-1"></i>Semua dinilai
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <!-- Deadline -->
                            @if($a->due_at)
                                <div class="small mb-3" style="color: var(--text-muted);">
                                    <i class="fas fa-clock me-1 text-danger"></i>
                                    Deadline: <strong>{{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</strong>
                                    @if(\Carbon\Carbon::parse($a->due_at)->isPast())
                                        <span class="status-badge ms-1" style="background: rgba(220,53,69,0.1); color: #dc3545; font-size: 0.6rem;">Berakhir</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            @if($submissionCount > 0)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span style="color: var(--text-muted);">Progress Penilaian</span>
                                        <span class="fw-bold" style="color: var(--primary);">{{ $gradedCount }}/{{ $submissionCount }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: var(--radius-sm); background-color: rgba(27,94,32,0.06);">
                                        @php $progressPercent = $submissionCount > 0 ? round(($gradedCount / $submissionCount) * 100) : 0; @endphp
                                        <div class="progress-bar" role="progressbar"
                                             style="width: {{ $progressPercent }}%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: var(--radius-sm);"
                                             aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Stats Row -->
                            <div class="d-flex gap-4 small">
                                <div>
                                    <i class="fas fa-users me-1" style="color: var(--primary);"></i>
                                    <span style="color: var(--text-muted);">{{ $submissionCount }} Dikumpulkan</span>
                                </div>
                                @if($avgScore !== null)
                                    <div>
                                        <i class="fas fa-chart-bar me-1" style="color: var(--secondary);"></i>
                                        <span style="color: var(--text-muted);">Rata-rata: <strong style="color: var(--primary);">{{ round($avgScore, 1) }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-2 d-flex justify-content-between align-items-center" onclick="event.stopPropagation();" style="border-top: 1px solid rgba(27,94,32,0.04);">
                            <small style="color: var(--text-muted);"><i class="fas fa-calendar me-1"></i>{{ $a->created_at->format('d M Y') }}</small>
                            <a href="{{ route('guru.assignments.show', $a) }}" class="btn btn-sm btn-outline-primary-theme" style="border-radius: var(--radius-sm);">
                                <i class="fas fa-eye me-1"></i> Lihat & Nilai
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $assignments->links() }}</div>
    @endif
@endsection
