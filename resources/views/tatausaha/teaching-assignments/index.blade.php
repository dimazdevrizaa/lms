@extends('layouts.lms')

@section('title', 'Penugasan Guru Mengajar')

@section('content')
<div class="teaching-assignments-page">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 reveal">
        <div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 4px;">
                Penugasan Guru Mengajar
            </h1>
            <p class="text-muted mb-0 small">Kelola guru yang mengambil kelas untuk mata pelajaran — dikelompokkan per kelas.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm btn-outline-primary-theme">
                <i class="fas fa-plus me-1"></i> Tambah Penugasan
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('tatausaha.teaching-assignments.index') }}" class="content-card reveal reveal-delay-1" style="border-top: 3px solid var(--secondary);">
        <div class="content-card-body" style="padding: 20px 28px;">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted"><i class="fas fa-calendar me-1"></i> Tahun Ajar</label>
                    <select name="academic_year_id" class="form-select form-select-sm ts-select">
                        <option value="">Semua Tahun Ajar</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" @selected($selectedYearId == $ay->id)>
                                {{ $ay->name }} {{ $ay->is_active ? '✅ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted"><i class="fas fa-chalkboard-user me-1"></i> Guru</label>
                    <select name="teacher_id" class="form-select form-select-sm ts-select">
                        <option value="">Semua Guru</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" @selected(request('teacher_id') == $t->id)>{{ $t->user->name ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-primary-theme">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('tatausaha.teaching-assignments.index') }}" class="btn btn-sm btn-outline-secondary-theme">
                        <i class="fas fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
                <div class="col-md-2 d-flex justify-content-end">
                    <a href="{{ route('tatausaha.teaching-assignments.print', ['academic_year_id' => $selectedYearId]) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-accent-theme">
                        <i class="fas fa-print me-1"></i> Cetak Semua
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Summary Stats --}}
    @if($orderedGroups->count() > 0)
        <div class="stats-grid reveal reveal-delay-2">
            <div class="stat-card stat-card--attendance">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--green">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="stat-label">Total Mata Pelajaran</div>
                <div class="stat-value stat-value--green">{{ $orderedGroups->count() }}</div>
            </div>
            <div class="stat-card stat-card--grades">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--gold">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="stat-label">Total Penugasan</div>
                <div class="stat-value stat-value--primary">{{ $orderedGroups->sum(fn($g) => $g['assignments']->count()) }}</div>
            </div>
            <div class="stat-card stat-card--behavior">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--deep">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="stat-label">Guru Terlibat</div>
                <div class="stat-value stat-value--primary">{{ $orderedGroups->flatMap(fn($g) => $g['assignments']->pluck('teacher_id'))->unique()->count() }}</div>
            </div>
        </div>
    @endif

    {{-- Cards per Kelas --}}
    @if($orderedGroups->count() > 0)
        <div class="d-flex justify-content-end mb-3 reveal reveal-delay-3">
            <button class="btn btn-sm btn-outline-secondary-theme" onclick="toggleAllCards()" id="toggleAllBtn">
                <i class="fas fa-compress-alt me-1"></i> Tutup Semua
            </button>
        </div>

        @foreach($orderedGroups as $subjectName => $group)
            @php
                $assignments = $group['assignments'];
                $firstSubject = $assignments->first()?->subject;
                $majorColors = [
                    'IPA' => ['bg' => 'linear-gradient(135deg, var(--primary), var(--secondary))', 'badge' => 'var(--secondary)'],
                    'IPS' => ['bg' => 'linear-gradient(135deg, #2c3e8c, #3498db)', 'badge' => '#2980b9'],
                ];
                $majorStyle = $majorColors[strtoupper($firstSubject->major ?? '')] ?? ['bg' => 'linear-gradient(135deg, var(--primary), var(--secondary))', 'badge' => 'var(--secondary)'];
                $safeId = Str::slug($subjectName);
            @endphp
            <div class="content-card class-card mb-3 overflow-hidden reveal reveal-delay-3" data-class-id="{{ $safeId }}" style="cursor: default;">
                {{-- Card Header --}}
                <div class="d-flex align-items-center justify-content-between py-3 px-4 class-card-header"
                     style="background: {{ $majorStyle['bg'] }}; cursor: pointer;"
                     onclick="toggleCard('{{ $safeId }}')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="class-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">{{ $subjectName }}</h5>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                @if($firstSubject && $firstSubject->major)
                                    <span class="badge" style="background-color: rgba(255,255,255,0.25); font-size: 0.7rem;">{{ $firstSubject->major }}</span>
                                    @if($firstSubject->code)
                                        <span class="badge" style="background-color: rgba(255,255,255,0.25); font-size: 0.7rem;">{{ $firstSubject->code }}</span>
                                    @endif
                                @endif
                                <span class="text-white-50 small">{{ $assignments->count() }} kelas</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('tatausaha.teaching-assignments.print', ['academic_year_id' => $selectedYearId, 'subject_name' => collect(explode(' ', $subjectName))->first()]) }}" 
                           target="_blank"
                           class="btn btn-sm"
                           style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);"
                           onclick="event.stopPropagation();"
                           title="Cetak mata pelajaran ini">
                            <i class="fas fa-print me-1"></i> Cetak
                        </a>
                        <i class="fas fa-chevron-up text-white toggle-icon" id="toggle-icon-{{ $safeId }}" style="transition: transform 0.3s ease;"></i>
                    </div>
                </div>

                {{-- Card Body (Table) --}}
                <div id="card-body-{{ $safeId }}" style="transition: max-height 0.4s ease; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">NO</th>
                                    <th>KELAS</th>
                                    <th>TINGKAT</th>
                                    <th>GURU PENGAMPU</th>
                                    <th style="text-align: center; width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $idx => $a)
                                    <tr class="assignment-row">
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold" style="color: var(--primary);">{{ $a->schoolClass->name ?? '-' }}</div>
                                            @if($a->schoolClass->major)
                                                <span class="badge bg-light text-muted" style="font-size: 0.65rem;">JURUSAN {{ $a->schoolClass->major }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ $a->schoolClass->level ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $a->teacher->user->name ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('tatausaha.teaching-assignments.edit', $a) }}" class="btn btn-sm btn-outline-accent-theme" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('tatausaha.teaching-assignments.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus penugasan {{ $a->subject->name ?? '-' }} untuk kelas {{ $a->schoolClass->name ?? '-' }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="content-card reveal reveal-delay-2">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="empty-state-text mb-3">Belum ada penugasan. Mulai buat penugasan untuk mengatur guru yang mengajar di tiap kelas.</div>
                <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm btn-outline-primary-theme">
                    <i class="fas fa-plus me-1"></i> Tambah Penugasan
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .teaching-assignments-page .content-card:hover {
        transform: none !important;
    }

    .class-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .teacher-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .assignment-row {
        transition: background-color 0.2s ease;
    }
    
    .assignment-row:hover {
        background-color: rgba(67, 160, 71, 0.04) !important;
    }

    .assignment-row td {
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }

    .toggle-icon.collapsed {
        transform: rotate(180deg);
    }

    .teaching-assignments-page .form-select,
    .teaching-assignments-page .form-select-sm {
        background-size: 14px 12px !important;
        padding-right: 1.5rem !important;
    }
</style>
@endpush

<script>
    function toggleCard(classId) {
        const body = document.getElementById('card-body-' + classId);
        const icon = document.getElementById('toggle-icon-' + classId);
        
        if (body.style.display === 'none') {
            body.style.display = '';
            icon.classList.remove('collapsed');
        } else {
            body.style.display = 'none';
            icon.classList.add('collapsed');
        }
    }

    function toggleAllCards() {
        const cards = document.querySelectorAll('[id^="card-body-"]');
        const btn = document.getElementById('toggleAllBtn');
        const anyVisible = Array.from(cards).some(card => card.style.display !== 'none');
        
        cards.forEach(card => {
            card.style.display = anyVisible ? 'none' : '';
        });
        
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            if (anyVisible) {
                icon.classList.add('collapsed');
            } else {
                icon.classList.remove('collapsed');
            }
        });

        btn.innerHTML = anyVisible 
            ? '<i class="fas fa-expand-alt me-1"></i> Buka Semua' 
            : '<i class="fas fa-compress-alt me-1"></i> Tutup Semua';
    }
</script>
@endsection
