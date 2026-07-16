@extends('layouts.lms')

@section('title', 'Riwayat Absensi - ' . $currentClass->name)

@section('content')
    <!-- Header -->
    <div class="mb-5 reveal">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('guru.attendances.index') }}" class="btn btn-outline-secondary-theme btn-sm" style="border-radius: var(--radius-sm);">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <div>
                    <nav aria-label="breadcrumb" class="mb-0">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('guru.attendances.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Riwayat Mapel</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $currentClass->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.75rem;">📜 Riwayat Presensi: {{ $currentClass->name }}</h1>
                </div>
            </div>
        </div>
        <p class="text-muted"><i class="fas fa-book me-1"></i> Mata Pelajaran: {{ $currentSubject->name }}</p>
    </div>

    @if($records->isEmpty())
        <div class="content-card reveal py-5">
            <div class="content-card-body text-center">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times text-success"></i>
                    </div>
                    <div class="empty-state-text">
                        <strong>Belum Ada Rekaman Presensi</strong><br>
                        Belum ada data kehadiran siswa yang tercatat untuk kelas ini.
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($records as $r)
                <div class="col reveal reveal-delay-{{ $loop->iteration }}">
                    <div class="content-card h-100 mb-0 shadow-sm border-0" style="border-radius: var(--radius-lg); transition: all 0.3s cubic-bezier(0.22, 0.61, 0.36, 1);">
                        <div class="content-card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-circle stat-icon-circle--green me-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">{{ \Carbon\Carbon::parse($r->date)->format('l, d M Y') }}</h6>
                                        @if($r->meeting)
                                            <span class="status-badge status-badge--hadir mt-1" style="font-size: 0.65rem; padding: 2px 8px;">Pertemuan {{ $r->meeting->number }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow: none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: var(--radius-sm);">
                                        <li><a class="dropdown-item" href="{{ route('guru.attendances.show', $r) }}"><i class="fas fa-eye me-2" style="color: var(--primary);"></i> Detail</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('guru.attendances.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus rekaman absensi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @php
                                $stats = $r->details->groupBy('status')->map->count();
                                $total = $r->details->count();
                                $present = $stats['hadir'] ?? 0;
                                $percent = $total > 0 ? round(($present / $total) * 100) : 0;
                            @endphp

                            <div class="mb-3 mt-4">
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted fw-semibold">Kehadiran: {{ $present }}/{{ $total }} Siswa</span>
                                    <span class="fw-bold" style="color: var(--primary);">{{ $percent }}%</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 100px; background: rgba(37, 103, 30, 0.05);">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%); border-radius: 100px;"></div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 pt-2">
                                <span class="status-badge status-badge--izin" style="font-size: 0.7rem; padding: 3px 10px;">Izin: {{ $stats['izin'] ?? 0 }}</span>
                                <span class="status-badge status-badge--sakit" style="font-size: 0.7rem; padding: 3px 10px;">Sakit: {{ $stats['sakit'] ?? 0 }}</span>
                                <span class="status-badge status-badge--alpa" style="font-size: 0.7rem; padding: 3px 10px;">Alpa: {{ $stats['alpa'] ?? 0 }}</span>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('guru.attendances.show', $r) }}" class="btn btn-outline-primary-theme btn-sm w-100 py-2 fw-semibold" style="border-radius: var(--radius-sm);">
                                    Buka Laporan Presensi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('styles')
<style>
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
</style>
@endpush
