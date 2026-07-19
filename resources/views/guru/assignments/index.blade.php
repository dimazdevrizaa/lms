@extends('layouts.lms')

@section('title', 'Tugas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--text-heading);">📋 Kelola Tugas</h1>
            <p class="mb-0" style="color: var(--text-muted);">Buat dan kelola tugas untuk siswa Anda</p>
        </div>
        <a class="btn btn-outline-secondary-theme" href="{{ route('guru.assignments.create') }}" style="border-radius: var(--radius-sm);">
            <i class="fas fa-plus me-2"></i> Buat Tugas Baru
        </a>
    </div>

    <!-- Filter Kelas -->
    @if(isset($teacherClasses) && $teacherClasses->count() > 0)
        <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
            <span class="small fw-bold me-1" style="color: var(--text-muted);"><i class="fas fa-filter me-1"></i>Kelas:</span>
            <a href="{{ route('guru.assignments.index') }}"
               class="btn btn-sm {{ !$selectedClassId ? 'text-white' : 'btn-outline-secondary' }}"
               style="{{ !$selectedClassId ? 'background-color: var(--primary); border-color: var(--primary);' : '' }} border-radius: var(--radius-sm);">
                Semua Kelas
            </a>
            @foreach($teacherClasses as $cls)
                <a href="{{ route('guru.assignments.index', ['class_id' => $cls->id]) }}"
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
                <div class="empty-state-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="empty-state-text">
                    <strong>Belum ada tugas</strong><br>
                    Mulai dengan membuat tugas baru untuk siswa Anda.
                </div>
                <a href="{{ route('guru.assignments.create') }}" class="btn btn-outline-secondary-theme mt-3" style="border-radius: var(--radius-sm);">Buat Sekarang</a>
            </div>
        </div>
    @else
        <div class="row">
            @forelse($assignments as $a)
                <div class="col-md-6 mb-4">
                    <div class="content-card h-100" style="cursor: pointer;"
                         onclick="window.location='{{ route('guru.assignments.show', $a) }}'">
                        <div class="content-card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: var(--primary); font-family: 'Plus Jakarta Sans', sans-serif;">{{ $a->title }}</h5>
                                    @if($a->due_at && \Carbon\Carbon::parse($a->due_at)->isFuture())
                                        <span class="status-badge status-badge--hadir">✓ Aktif</span>
                                    @else
                                        <span class="status-badge" style="background: rgba(220,53,69,0.1); color: #C62828;">⊘ Ditutup</span>
                                    @endif
                                    @if($a->isOnline())
                                        <span class="status-badge" style="background: rgba(13,110,253,0.1); color: #0d6efd;"><i class="fas fa-laptop me-1"></i>Online</span>
                                    @elseif($a->type === 'external')
                                        <span class="status-badge" style="background: rgba(25,135,84,0.1); color: var(--primary); font-weight: 700; border: none; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm);"><i class="fas fa-link me-1"></i>Kuis Online</span>
                                    @else
                                        <span class="status-badge" style="background: rgba(108,117,125,0.1); color: #6c757d;"><i class="fas fa-file-pdf me-1"></i>PDF</span>
                                    @endif
                                </div>
                                <small style="color: var(--text-muted);"><i class="fas fa-calendar-check me-1"></i> {{ $a->created_at->format('d M Y') }}</small>
                            </div>

                            <div class="mb-4">
                                <span class="d-inline-block small me-3" style="color: var(--text-muted);"><i class="fas fa-door-open me-1" style="color: var(--primary);"></i> {{ $a->schoolClass?->name ?? 'Tanpa Kelas' }}</span>
                                <span class="d-inline-block small" style="color: var(--text-muted);"><i class="fas fa-book me-1" style="color: var(--secondary);"></i> {{ $a->subject?->name ?? 'Tanpa Mapel' }}</span>
                            </div>

                            @if($a->due_at)
                                <div class="p-2 rounded mb-3 small" style="background: var(--bg-body); border-radius: var(--radius-sm);">
                                    <i class="fas fa-clock text-danger me-2"></i> Deadline: <strong class="text-danger">{{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</strong>
                                </div>
                            @endif

                            @if($a->meeting)
                                <div class="p-2 rounded small mb-3" style="background: var(--bg-body); border-radius: var(--radius-sm);">
                                    <i class="fas fa-calendar-alt me-2" style="color: var(--accent);"></i> Bagian dari: <strong style="color: var(--primary);">Pertemuan {{ $a->meeting->number }}</strong>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="small fw-bold" style="color: var(--text-muted);">
                                    <i class="fas fa-user-check me-1" style="color: var(--primary);"></i> {{ $a->submissions_count ?? $a->submissions->count() }} Terkumpul
                                </div>
                                @if($a->file_path)
                                    <span class="status-badge" style="background: rgba(220,53,69,0.08); color: #dc3545; cursor: pointer;" onclick="event.stopPropagation(); window.open('{{ route('assignments.download', $a) }}', '_blank')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="px-4 pb-3 d-flex justify-content-end gap-2" onclick="event.stopPropagation();" style="border-top: 1px solid rgba(27,94,32,0.04); padding-top: 12px;">
                            <a href="{{ route('guru.assignments.edit', $a) }}" class="btn btn-sm btn-outline-primary-theme" style="border-radius: var(--radius-sm);">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('guru.assignments.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: var(--radius-sm);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>

        <div class="mt-4">{{ $assignments->links() }}</div>
    @endif
@endsection
