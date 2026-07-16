@extends('layouts.lms')

@section('title', 'Absensi Kelas - ' . $class->name)

@section('content')
    <div class="page-header-banner reveal">
        <div class="page-header-banner-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif;">📋 Absensi Harian Kelas {{ $class->name }}</h1>
                    <p>Kelola absensi siswa perwalian Anda secara berkala</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('guru.classroom.index') }}" class="btn btn-outline-light d-inline-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('guru.classroom.attendance.create', $class) }}" class="btn btn-light d-inline-flex align-items-center gap-2" style="color: var(--primary) !important; font-weight: 700;">
                        <i class="fas fa-plus"></i> Input Absensi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm reveal" role="alert" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Daftar Absensi -->
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-history"></i>
            </div>
            <h5 class="content-card-title">Riwayat Absensi Kehadiran</h5>
        </div>
        <div class="content-card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>📅 Tanggal</th>
                                <th class="text-center">✓ Hadir</th>
                                <th class="text-center">📋 Izin</th>
                                <th class="text-center">🏥 Sakit</th>
                                <th class="text-center">❌ Alpa</th>
                                <th class="text-center" style="width: 160px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                @php
                                    $hadir = $attendance->details->whereIn('status', ['hadir'])->count();
                                    $izin = $attendance->details->where('status', 'izin')->count();
                                    $sakit = $attendance->details->where('status', 'sakit')->count();
                                    $alpa = $attendance->details->where('status', 'alpa')->count();
                                @endphp
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $attendance->date->translatedFormat('d F Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill fw-bold px-3 py-2" style="background-color: rgba(67, 160, 71, 0.12); color: #2E7D32;">
                                            {{ $hadir }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill fw-bold px-3 py-2" style="background-color: rgba(249, 168, 37, 0.12); color: #B26A00;">
                                            {{ $izin }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill fw-bold px-3 py-2" style="background-color: rgba(255, 152, 0, 0.12); color: #E65100;">
                                            {{ $sakit }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill fw-bold px-3 py-2" style="background-color: rgba(198, 40, 40, 0.10); color: #C62828;">
                                            {{ $alpa }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('guru.classroom.attendance.show', [$class, $attendance]) }}" class="btn btn-sm btn-outline-primary-theme px-3">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="empty-state py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="empty-state-text mt-3">Belum Ada Data Absensi</h5>
                    <p class="text-muted">Mulai catat kehadiran harian kelas untuk memantau keaktifan siswa.</p>
                    <a href="{{ route('guru.classroom.attendance.create', $class) }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Input Absensi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
