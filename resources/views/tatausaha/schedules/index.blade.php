@extends('layouts.lms')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="schedules-page">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 reveal">
        <div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 4px;">
                Jadwal Pelajaran Mingguan
            </h1>
            <p class="text-muted mb-0 small">Susun jadwal mata pelajaran per kelas untuk setiap hari dalam seminggu.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tatausaha.schedules.time-slots', ['academic_year_id' => $selectedYearId]) }}" class="btn btn-sm btn-outline-primary-theme">
                <i class="fas fa-clock me-1"></i> Kelola Jam Pelajaran
            </a>
            <a href="{{ route('tatausaha.schedules.print', ['academic_year_id' => $selectedYearId]) }}" target="_blank" class="btn btn-sm btn-outline-accent-theme">
                <i class="fas fa-print me-1"></i> Cetak Semua Jadwal
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('tatausaha.schedules.index') }}" class="content-card mb-4 reveal reveal-delay-1" style="border-top: 3px solid var(--secondary);">
        <div class="content-card-body" style="padding: 20px 28px;">
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
                    <button type="submit" class="btn btn-sm btn-outline-primary-theme">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if(!$selectedYearId)
        <div class="content-card reveal reveal-delay-2">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="empty-state-text">Pilih tahun ajar terlebih dahulu untuk melihat dan mengelola jadwal.</div>
            </div>
        </div>
    @else
        {{-- Warning --}}
        @if($timeSlotCount == 0)
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 reveal reveal-delay-2" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    Belum ada jam pelajaran untuk tahun ajar ini. 
                    <a href="{{ route('tatausaha.schedules.time-slots', ['academic_year_id' => $selectedYearId]) }}" class="fw-bold">Kelola Jam Pelajaran</a> terlebih dahulu.
                </div>
            </div>
        @endif

        {{-- Summary Stats --}}
        <div class="stats-grid reveal reveal-delay-2">
            <div class="stat-card stat-card--attendance">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--green">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
                <div class="stat-label">Total Kelas</div>
                <div class="stat-value stat-value--green">{{ $classes->count() }}</div>
            </div>
            <div class="stat-card stat-card--grades">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--gold">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-label">Jam Pelajaran</div>
                <div class="stat-value stat-value--primary">{{ $timeSlotCount }}</div>
                <div class="stat-sub">slot tersedia</div>
            </div>
            <div class="stat-card stat-card--behavior">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon-circle stat-icon-circle--deep">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-label">Kelas Terjadwal</div>
                <div class="stat-value stat-value--primary">{{ count(array_filter($filledCounts)) }}</div>
                <div class="stat-sub">dari {{ $classes->count() }} kelas</div>
            </div>
        </div>

        {{-- Class Cards Grid --}}
        <div class="row g-3 reveal reveal-delay-3">
            @foreach($classes as $class)
                @php
                    $filled = $filledCounts[$class->id] ?? 0;
                    $totalSlots = $timeSlotCount * 6;
                    $percentage = $totalSlots > 0 ? round(($filled / $totalSlots) * 100) : 0;
                    $majorColors = [
                        'IPA' => ['gradient' => 'linear-gradient(135deg, var(--primary), var(--secondary))', 'light' => 'rgba(67, 160, 71, 0.1)'],
                        'IPS' => ['gradient' => 'linear-gradient(135deg, #2c3e8c, #3498db)', 'light' => 'rgba(52, 152, 219, 0.1)'],
                    ];
                    $style = $majorColors[strtoupper($class->major ?? '')] ?? ['gradient' => 'linear-gradient(135deg, var(--primary), var(--secondary))', 'light' => 'rgba(67, 160, 71, 0.1)'];
                @endphp
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('tatausaha.schedules.edit', ['schoolClass' => $class->id, 'academic_year_id' => $selectedYearId]) }}" class="text-decoration-none">
                        <div class="content-card class-schedule-card h-100 overflow-hidden" style="margin-bottom: 0;">
                            <div class="py-3 px-3 text-white" style="background: {{ $style['gradient'] }};">
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
                            <div class="content-card-body py-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Terisi</span>
                                    <span class="small fw-bold" style="color: var(--primary);">{{ $filled }} / {{ $totalSlots }}</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 3px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background: {{ $style['gradient'] }};" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="small fw-semibold" style="color: {{ $filled > 0 ? 'var(--secondary)' : '#aaa' }};">
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
            <div class="content-card reveal reveal-delay-3">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="empty-state-text">Belum Ada Kelas. Tambahkan kelas terlebih dahulu melalui menu Data Kelas.</div>
                </div>
            </div>
        @endif
    @endif
</div>

@push('styles')
<style>
    .schedules-page .content-card:hover {
        transform: none !important;
    }

    .class-schedule-card {
        transition: all 0.3s cubic-bezier(0.22, 0.61, 0.36, 1);
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
@endpush
@endsection
