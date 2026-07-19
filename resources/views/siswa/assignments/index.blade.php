@extends('layouts.lms')

@section('title', 'Tugas')

@section('content')
    <!-- Header Banner -->
    <div class="page-header-banner reveal">
        <div class="page-header-banner-inner">
            <h1>📋 Tugas Saya</h1>
            <p>Kumpulkan dan pantau kemajuan tugas Anda</p>
        </div>
    </div>

    @if(!$student)
        <div class="alert alert-warning reveal reveal-delay-1">
            <strong>⚠️ Perhatian</strong>
            <p class="mb-0 mt-2">Profil siswa belum terdaftar. Silakan minta Tata Usaha membuat data siswa untuk akun ini.</p>
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="content-card reveal reveal-delay-1">
            <div class="content-card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="empty-state-text">Belum ada tugas. Guru akan memberikan tugas untuk Anda. Pantau halaman ini untuk tugas terbaru.</div>
                </div>
            </div>
        </div>
    @else
        <!-- Assignments Cards -->
        <div class="row">
            @forelse($assignments as $assignment)
                @php
                    $isSubmitted = in_array($assignment->id, $submittedIds ?? []);
                    $isDeadlinePassed = $assignment->due_at && \Carbon\Carbon::parse($assignment->due_at)->isPast();
                    $isDeadlineSoon = $assignment->due_at && !$isDeadlinePassed && \Carbon\Carbon::parse($assignment->due_at)->diffInHours(now()) <= 24;
                @endphp
                <div class="col-md-6 mb-4 reveal reveal-delay-{{ ($loop->index % 4) + 1 }}">
                    <div class="content-card h-100" style="border-top: none;">
                        <div class="content-card-header">
                            <div class="content-card-header-icon">
                                <i class="fas {{ $assignment->isOnline() ? 'fa-laptop' : ($assignment->type === 'external' ? 'fa-link' : 'fa-file-pdf') }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="content-card-title mb-0">{{ $assignment->title }}</h5>
                            </div>
                            @if(!$assignment->due_at || !$isDeadlinePassed)
                                <span class="status-badge status-badge--aktif"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                            @else
                                <span class="status-badge status-badge--ditutup"><i class="fas fa-times-circle me-1"></i> Ditutup</span>
                            @endif
                        </div>
                        <div class="content-card-body">
                            <!-- Type & Status Badges -->
                            <div class="d-flex gap-1 flex-wrap mb-3">
                                @if($assignment->isOnline())
                                    <span class="status-badge status-badge--online"><i class="fas fa-laptop me-1"></i>Online</span>
                                @elseif($assignment->type === 'external')
                                    <span class="status-badge" style="background: rgba(25, 135, 84, 0.1); color: var(--primary); font-weight: 700; border: none; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm);"><i class="fas fa-link me-1"></i>Kuis Online</span>
                                @else
                                    <span class="status-badge status-badge--pdf"><i class="fas fa-file-pdf me-1"></i>PDF</span>
                                @endif
                                @if($isSubmitted)
                                    <span class="status-badge status-badge--hadir"><i class="fas fa-check me-1"></i>Sudah Dikumpulkan</span>
                                @elseif($isDeadlinePassed)
                                    <span class="status-badge status-badge--alpa"><i class="fas fa-times-circle me-1"></i>Tidak Dikumpulkan</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-muted small mb-3">{{ Str::limit($assignment->description, 100) }}</p>

                            <!-- Deadline -->
                            @if($assignment->due_at)
                                <div class="alert {{ $isDeadlinePassed ? 'alert-danger' : ($isDeadlineSoon ? 'alert-warning' : 'alert-success') }}" style="padding: 10px 14px; margin-bottom: 15px;">
                                    <small>
                                        @if($isDeadlinePassed)
                                            <strong>⛔ Deadline Terlewat:</strong>
                                        @elseif($isDeadlineSoon)
                                            <strong>⚠️ Deadline Segera:</strong>
                                        @else
                                            <strong>⏰ Deadline:</strong>
                                        @endif
                                        {{ \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') }}
                                        @if(!$isDeadlinePassed)
                                            <br><span class="text-muted">({{ \Carbon\Carbon::parse($assignment->due_at)->diffForHumans() }})</span>
                                        @endif
                                    </small>
                                </div>
                            @endif

                            @if($student)
                                @if($assignment->isOnline())
                                    {{-- Online Assignment --}}
                                    @if($isSubmitted)
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-chart-bar me-1"></i> Lihat Hasil
                                        </a>
                                    @elseif($isDeadlinePassed)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-lock me-1"></i> Deadline Terlewat
                                        </button>
                                    @else
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-outline-primary-theme w-100">
                                            <i class="fas fa-pen me-1"></i> Kerjakan Online
                                        </a>
                                    @endif
                                @elseif($assignment->type === 'external')
                                    {{-- External Quiz Assignment --}}
                                    @if($isSubmitted)
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-chart-bar me-1"></i> Lihat Hasil
                                        </a>
                                    @elseif($isDeadlinePassed)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-lock me-1"></i> Deadline Terlewat
                                        </button>
                                    @else
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-outline-primary-theme w-100">
                                            <i class="fas fa-link me-1"></i> Kerjakan Kuis Online
                                        </a>
                                    @endif
                                @else
                                    {{-- PDF Assignment --}}
                                    @if($isSubmitted)
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-chart-bar me-1"></i> Lihat Hasil
                                        </a>
                                    @elseif($isDeadlinePassed)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-lock me-1"></i> Deadline Terlewat
                                        </button>
                                    @else
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-outline-primary-theme w-100">
                                            <i class="fas fa-folder-open me-1"></i> Detail & Kerjakan Tugas
                                        </a>
                                    @endif
                                @endif
                            @else
                                <div class="alert alert-warning small mb-0">
                                    Data siswa belum terdaftar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="content-card">
                        <div class="content-card-body">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="empty-state-text">Belum ada tugas.</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
@endsection
