@extends('layouts.lms')

@section('title', 'Penilaian Tugas')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📝 Penilaian Tugas</h1>
        <p class="text-muted mb-0">Lihat dan nilai tugas yang telah dikumpulkan siswa</p>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #25671E !important;">
                <div class="card-body text-center py-4">
                    <div class="display-5 fw-bold" style="color: #25671E;">{{ $totalAssignments }}</div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Total Tugas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #48A111 !important;">
                <div class="card-body text-center py-4">
                    <div class="display-5 fw-bold" style="color: #48A111;">{{ $totalSubmissions }}</div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Total Dikumpulkan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #F2B50B !important;">
                <div class="card-body text-center py-4">
                    <div class="display-5 fw-bold" style="color: #F2B50B;">{{ $pendingGrading }}</div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Belum Dinilai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #48A111 !important;">
                <div class="card-body text-center py-4">
                    <div class="display-5 fw-bold" style="color: #48A111;">{{ $gradedSubmissions }}</div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Sudah Dinilai</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Kelas -->
    @if(isset($teacherClasses) && $teacherClasses->count() > 0)
        <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
            <span class="text-muted small fw-bold me-1"><i class="fas fa-filter me-1"></i>Kelas:</span>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->except('class_id'), [])) }}" 
               class="btn btn-sm {{ !$selectedClassId ? 'text-white' : 'btn-outline-secondary' }}"
               style="{{ !$selectedClassId ? 'background-color: #25671E; border-color: #25671E;' : '' }}">
                Semua Kelas
            </a>
            @foreach($teacherClasses as $cls)
                <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['class_id' => $cls->id])) }}" 
                   class="btn btn-sm {{ $selectedClassId == $cls->id ? 'text-white' : 'btn-outline-secondary' }}"
                   style="{{ $selectedClassId == $cls->id ? 'background-color: #25671E; border-color: #25671E;' : '' }}">
                    {{ $cls->name }}
                </a>
            @endforeach
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-clipboard-check fa-4x mb-4" style="color: #ddd;"></i>
                <h5 class="mb-3">📭 Belum ada tugas</h5>
                <p class="text-muted mb-0">Tidak ada tugas pada filter kelas ini atau Anda belum membuat tugas apapun.</p>
            </div>
        </div>
    @else
        <!-- Filter Tabs -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => null])) }}" 
               class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-outline-secondary' }}" 
               style="{{ !request('filter') ? 'background-color: #25671E; border-color: #25671E;' : '' }}">
                Semua ({{ $totalAssignments }})
            </a>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => 'pending'])) }}" 
               class="btn btn-sm {{ request('filter') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fas fa-clock me-1"></i> Perlu Dinilai ({{ $pendingGrading > 0 ? $pendingGrading : 0 }})
            </a>
            <a href="{{ route('guru.assignments.grading', array_merge(request()->query(), ['filter' => 'graded'])) }}" 
               class="btn btn-sm {{ request('filter') === 'graded' ? 'btn-success' : 'btn-outline-success' }}">
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
                    <div class="card h-100 border-0 shadow-sm overflow-hidden grading-card" 
                         style="cursor: pointer; transition: all 0.3s ease; border-left: 5px solid {{ $ungradedCount > 0 ? '#F2B50B' : '#48A111' }} !important;"
                         onclick="window.location='{{ route('guru.assignments.show', $a) }}'">
                        
                        <div class="card-body p-4">
                            <!-- Title & Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="color: #25671E;">{{ $a->title }}</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge" style="background-color: #48A111; font-size: 0.65rem;">{{ $a->schoolClass?->name ?? '-' }}</span>
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $a->subject?->name ?? '-' }}</span>
                                        @if($a->isOnline())
                                            <span class="badge bg-primary" style="font-size: 0.65rem;"><i class="fas fa-laptop me-1"></i>Online</span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.65rem;"><i class="fas fa-file-pdf me-1"></i>PDF</span>
                                        @endif
                                    </div>
                                </div>
                                @if($ungradedCount > 0)
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $ungradedCount }} belum dinilai
                                    </span>
                                @else
                                    @if($submissionCount > 0)
                                        <span class="badge bg-success rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                            <i class="fas fa-check-circle me-1"></i>Semua dinilai
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <!-- Deadline -->
                            @if($a->due_at)
                                <div class="small text-muted mb-3">
                                    <i class="fas fa-clock me-1 text-danger"></i> 
                                    Deadline: <strong>{{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</strong>
                                    @if(\Carbon\Carbon::parse($a->due_at)->isPast())
                                        <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Berakhir</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            @if($submissionCount > 0)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Progress Penilaian</span>
                                        <span class="fw-bold" style="color: #25671E;">{{ $gradedCount }}/{{ $submissionCount }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 10px; background-color: #eee;">
                                        @php $progressPercent = $submissionCount > 0 ? round(($gradedCount / $submissionCount) * 100) : 0; @endphp
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $progressPercent }}%; background: linear-gradient(90deg, #25671E, #48A111); border-radius: 10px;"
                                             aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Stats Row -->
                            <div class="d-flex gap-4 small">
                                <div>
                                    <i class="fas fa-users text-primary me-1"></i>
                                    <span class="text-muted">{{ $submissionCount }} Dikumpulkan</span>
                                </div>
                                @if($avgScore !== null)
                                    <div>
                                        <i class="fas fa-chart-bar text-success me-1"></i>
                                        <span class="text-muted">Rata-rata: <strong style="color: #25671E;">{{ round($avgScore, 1) }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center" onclick="event.stopPropagation();">
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $a->created_at->format('d M Y') }}</small>
                            <a href="{{ route('guru.assignments.show', $a) }}" class="btn btn-sm" style="background-color: #25671E; color: white; border: none;">
                                <i class="fas fa-eye me-1"></i> Lihat & Nilai
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .grading-card:hover {
                transform: translateY(-5px) !important;
                box-shadow: 0 10px 30px rgba(37, 103, 30, 0.15) !important;
            }
        </style>

        <div class="mt-4">{{ $assignments->links() }}</div>
    @endif
@endsection
