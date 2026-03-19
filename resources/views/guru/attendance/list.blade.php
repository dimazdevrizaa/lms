@extends('layouts.lms')

@section('title', 'Riwayat Absensi - ' . $currentClass->name)

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('guru.attendances.index') }}" style="color: #48A111;">Riwayat Mapel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $currentClass->name }}</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0">📜 Riwayat Presensi: {{ $currentClass->name }}</h1>
            </div>
            <a href="{{ route('guru.attendances.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
        <p class="text-muted"><i class="fas fa-book me-1"></i> Mata Pelajaran: {{ $currentSubject->name }}</p>
    </div>

    @if($records->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
            <p>Belum ada rekaman presensi untuk kelas ini.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($records as $r)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm transition-hover">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle p-2 rounded-circle me-3">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($r->date)->format('l, d M Y') }}</h6>
                                        @if($r->meeting)
                                            <span class="badge bg-light text-dark border mt-1">Pertemuan {{ $r->meeting->number }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item" href="{{ route('guru.attendances.show', $r) }}"><i class="fas fa-eye me-2"></i> Detail</a></li>
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

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">Kehadiran: {{ $present }}/{{ $total }} Siswa</span>
                                    <span class="fw-bold">{{ $percent }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 pt-2">
                                <span class="badge bg-info-subtle text-info small border border-info-subtle">Izin: {{ $stats['izin'] ?? 0 }}</span>
                                <span class="badge bg-warning-subtle text-warning small border border-warning-subtle">Sakit: {{ $stats['sakit'] ?? 0 }}</span>
                                <span class="badge bg-danger-subtle text-danger small border border-danger-subtle">Alpa: {{ $stats['alpa'] ?? 0 }}</span>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('guru.attendances.show', $r) }}" class="btn btn-sm btn-outline-success w-100">
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

<style>
    .transition-hover { transition: transform 0.2s; }
    .transition-hover:hover { transform: translateY(-5px); }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
</style>
