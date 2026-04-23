@extends('layouts.lms')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="schedules-page">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-2">📅 Jadwal Pelajaran Mingguan</h1>
            <p class="text-muted mb-0">Susun jadwal mata pelajaran per kelas untuk setiap hari dalam seminggu.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tatausaha.schedules.time-slots', ['academic_year_id' => $selectedYearId]) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-clock me-1"></i> Kelola Jam Pelajaran
            </a>
            <a href="{{ route('tatausaha.schedules.print', ['academic_year_id' => $selectedYearId]) }}" target="_blank" class="btn btn-sm btn-outline-danger shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak Semua Jadwal
            </a>
        </div>
    </div>

    <!-- Filter Tahun Ajar -->
    <form method="GET" action="{{ route('tatausaha.schedules.index') }}" class="card mb-4 filter-card" style="border-top: 4px solid #48A111;">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted"><i class="fas fa-calendar me-1"></i> Tahun Ajar</label>
                    <select name="academic_year_id" class="form-select form-select-sm ts-select">
                        <option value="">Pilih Tahun Ajar</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" @selected($selectedYearId == $ay->id)>
                                {{ $ay->name }} {{ $ay->is_active ? '✅ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" style="background-color: #25671E; border: none;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if(!$selectedYearId)
        <div class="card text-center py-5 border-0 shadow-sm" style="cursor: default;">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-4x mb-4" style="color: #e0e0e0;"></i>
                <h5 class="mb-2" style="color: #25671E;">Pilih Tahun Ajar</h5>
                <p class="text-muted">Pilih tahun ajar terlebih dahulu untuk melihat dan mengelola jadwal.</p>
            </div>
        </div>
    @else
        <!-- Info -->
        @if($timeSlotCount == 0)
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    Belum ada jam pelajaran untuk tahun ajar ini. 
                    <a href="{{ route('tatausaha.schedules.time-slots', ['academic_year_id' => $selectedYearId]) }}" class="fw-bold">Kelola Jam Pelajaran</a> terlebih dahulu.
                </div>
            </div>
        @endif

        <!-- Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #25671E, #48A111);">
                            <i class="fas fa-door-open text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Total Kelas</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ $classes->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F2B50B, #f5c842);">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Jam Pelajaran</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ $timeSlotCount }} slot</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm h-100" style="cursor: default;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4a90d9, #6bb3f0);">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold">Kelas Terjadwal</div>
                            <div class="h4 mb-0 fw-bold" style="color: #25671E;">{{ count(array_filter($filledCounts)) }} / {{ $classes->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Kelas -->
        <div class="row g-3">
            @foreach($classes as $class)
                @php
                    $filled = $filledCounts[$class->id] ?? 0;
                    $totalSlots = $timeSlotCount * 6; // 6 hari
                    $percentage = $totalSlots > 0 ? round(($filled / $totalSlots) * 100) : 0;
                    $majorColors = [
                        'IPA' => ['gradient' => 'linear-gradient(135deg, #1a7a3a, #2ecc71)', 'light' => 'rgba(46, 204, 113, 0.1)'],
                        'IPS' => ['gradient' => 'linear-gradient(135deg, #2c3e8c, #3498db)', 'light' => 'rgba(52, 152, 219, 0.1)'],
                    ];
                    $style = $majorColors[strtoupper($class->major ?? '')] ?? ['gradient' => 'linear-gradient(135deg, #25671E, #48A111)', 'light' => 'rgba(72, 161, 17, 0.1)'];
                @endphp
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('tatausaha.schedules.edit', ['schoolClass' => $class->id, 'academic_year_id' => $selectedYearId]) }}" class="text-decoration-none">
                        <div class="card class-schedule-card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="card-header py-3 px-3 border-0 text-white" style="background: {{ $style['gradient'] }};">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="class-icon-sm">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-white">{{ $class->name }}</h6>
                                        @if($class->major)
                                            <span class="badge" style="background: rgba(255,255,255,0.25); font-size: 0.65rem;">{{ $class->major }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Terisi</span>
                                    <span class="small fw-bold" style="color: #25671E;">{{ $filled }} / {{ $totalSlots }}</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 3px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background: {{ $style['gradient'] }};" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="small fw-semibold" style="color: {{ $filled > 0 ? '#48A111' : '#aaa' }};">
                                        @if($filled > 0)
                                            <i class="fas fa-edit me-1"></i> Edit Jadwal
                                        @else
                                            <i class="fas fa-plus-circle me-1"></i> Buat Jadwal
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @if($classes->isEmpty())
            <div class="card text-center py-5 border-0 shadow-sm" style="cursor: default;">
                <div class="card-body">
                    <i class="fas fa-door-open fa-4x mb-4" style="color: #e0e0e0;"></i>
                    <h5 class="mb-2" style="color: #25671E;">Belum Ada Kelas</h5>
                    <p class="text-muted">Tambahkan kelas terlebih dahulu melalui menu Data Kelas.</p>
                </div>
            </div>
        @endif
    @endif
</div>

<style>
    .schedules-page .filter-card:hover {
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

    .class-schedule-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 16px !important;
    }

    .class-schedule-card:hover {
        transform: translateY(-6px) scale(1.02) !important;
        box-shadow: 0 12px 30px rgba(37, 103, 30, 0.15) !important;
    }

    .class-icon-sm {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }
</style>
@endsection
