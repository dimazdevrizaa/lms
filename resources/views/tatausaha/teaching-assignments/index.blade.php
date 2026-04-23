@extends('layouts.lms')

@section('title', 'Penugasan Guru Mengajar')

@section('content')
<div class="teaching-assignments-page">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-2">📋 Penugasan Guru Mengajar</h1>
            <p class="text-muted mb-0">Kelola guru yang mengambil kelas untuk mata pelajaran — dikelompokkan per kelas.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm shadow-sm" style="background-color: #48A111; color: white; border: none;">
                <i class="fas fa-plus me-1"></i> Tambah Penugasan
            </a>

        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('tatausaha.teaching-assignments.index') }}" class="card mb-4 filter-card" style="border-top: 4px solid #48A111;">
        <div class="card-body py-3">
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
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" style="background-color: #25671E; border: none;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('tatausaha.teaching-assignments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
                <div class="col-md-2 d-flex justify-content-end">
                    <a href="{{ route('tatausaha.teaching-assignments.print', ['academic_year_id' => $selectedYearId]) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-danger shadow-sm">
                        <i class="fas fa-print me-1"></i> Cetak Semua
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Summary Stats -->
    @if($orderedGroups->count() > 0)
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #25671E, #48A111);">
                            <i class="fas fa-door-open text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Total Mata Pelajaran</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ $orderedGroups->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F2B50B, #f5c842);">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Total Penugasan</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ $orderedGroups->sum(fn($g) => $g['assignments']->count()) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4a90d9, #6bb3f0);">
                            <i class="fas fa-chalkboard-user text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Guru Terlibat</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ $orderedGroups->flatMap(fn($g) => $g['assignments']->pluck('teacher_id'))->unique()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cards per Kelas -->
    @if($orderedGroups->count() > 0)
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleAllCards()" id="toggleAllBtn">
                <i class="fas fa-compress-alt me-1"></i> Tutup Semua
            </button>
        </div>

        @foreach($orderedGroups as $subjectName => $group)
            @php
                $assignments = $group['assignments'];
                $firstSubject = $assignments->first()?->subject;
                $majorColors = [
                    'IPA' => ['bg' => 'linear-gradient(135deg, #1a7a3a, #2ecc71)', 'badge' => '#27ae60'],
                    'IPS' => ['bg' => 'linear-gradient(135deg, #2c3e8c, #3498db)', 'badge' => '#2980b9'],
                ];
                $majorStyle = $majorColors[strtoupper($firstSubject->major ?? '')] ?? ['bg' => 'linear-gradient(135deg, #25671E, #48A111)', 'badge' => '#48A111'];
                $safeId = Str::slug($subjectName);
            @endphp
            <div class="card class-card mb-4 border-0 shadow-sm overflow-hidden" style="cursor: default;" data-class-id="{{ $safeId }}">
                <!-- Card Header -->
                <div class="card-header class-card-header d-flex align-items-center justify-content-between py-3 px-4"
                     style="background: {{ $majorStyle['bg'] }}; border: none; cursor: pointer;"
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

                <!-- Card Body (Table) -->
                <div class="card-body p-0" id="card-body-{{ $safeId }}" style="transition: max-height 0.4s ease; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                    <th class="ps-4" style="width: 50px; color: #6c757d; font-weight: 600; font-size: 0.8rem;">NO</th>
                                    <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">KELAS</th>
                                    <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">TINGKAT</th>
                                    <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">GURU PENGAMPU</th>
                                    <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem; text-align: center; width: 140px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $idx => $a)
                                    <tr class="assignment-row">
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold" style="color: #25671E;">{{ $a->schoolClass->name ?? '-' }}</div>
                                            @if($a->schoolClass->major)
                                                <span class="badge bg-light text-muted" style="font-size: 0.65rem;">JURUSAN {{ $a->schoolClass->major }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ $a->schoolClass->level ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="teacher-avatar" style="background: {{ $majorStyle['bg'] }};">
                                                    {{ substr($a->teacher->user->name ?? '?', 0, 1) }}
                                                </div>
                                                <span>{{ $a->teacher->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('tatausaha.teaching-assignments.edit', $a) }}" class="btn btn-sm btn-outline-warning" title="Edit">
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
        <div class="card text-center py-5 border-0 shadow-sm" style="cursor: default;">
            <div class="card-body">
                <i class="fas fa-clipboard-list fa-4x mb-4" style="color: #e0e0e0;"></i>
                <h5 class="mb-2" style="color: #25671E;">Belum ada penugasan</h5>
                <p class="text-muted mb-4">Mulai buat penugasan untuk mengatur guru yang mengajar di tiap kelas.</p>
                <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm" style="background-color: #48A111; color: white;">
                    <i class="fas fa-plus me-1"></i> Tambah Penugasan
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .teaching-assignments-page .filter-card:hover {
        transform: none !important;
        cursor: default;
    }

    .stat-card:hover {
        transform: none !important;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .class-card {
        border-radius: 16px !important;
        overflow: hidden;
    }

    .class-card:hover {
        transform: none !important;
        cursor: default;
    }

    .class-card-header:hover {
        filter: brightness(1.05);
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
        background-color: rgba(72, 161, 17, 0.04) !important;
    }

    .assignment-row td {
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }

    .toggle-icon.collapsed {
        transform: rotate(180deg);
    }

    /* Fix form-select in this page */
    .teaching-assignments-page .form-select,
    .teaching-assignments-page .form-select-sm {
        background-size: 14px 12px !important;
        padding-right: 1.5rem !important;
    }
</style>

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
