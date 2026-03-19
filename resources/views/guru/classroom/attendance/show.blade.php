@extends('layouts.lms')

@section('title', 'Detail Absensi Kelas - ' . $class->name)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <a href="{{ route('guru.classroom.attendance', $class) }}" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="h3 mb-2 mt-2">🔍 Detail Absensi Harian</h1>
            <p class="text-muted mb-0">Kelas {{ $class->name }} - {{ $attendance->date->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Stats Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #25671E !important;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4" style="color: #25671E;">Ringkasan Kehadiran</h5>
                    
                    @php
                        $stats = $attendance->details->groupBy('status')->map->count();
                    @endphp

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #E8F5E9;">
                            <span class="fw-bold" style="color: #2E7D32;">Hadir</span>
                            <span class="badge bg-success rounded-pill px-3">{{ $stats['hadir'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #E3F2FD;">
                            <span class="fw-bold" style="color: #1976D2;">Izin</span>
                            <span class="badge bg-info text-dark rounded-pill px-3">{{ $stats['izin'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #FFFDE7;">
                            <span class="fw-bold" style="color: #FBC02D;">Sakit</span>
                            <span class="badge bg-warning text-dark rounded-pill px-3">{{ $stats['sakit'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #FFEBEE;">
                            <span class="fw-bold" style="color: #C62828;">Alpa</span>
                            <span class="badge bg-danger rounded-pill px-3">{{ $stats['alpa'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm text-wrap">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #F7F0F0;">
                            <tr>
                                <th class="ps-4" style="color: #25671E; font-weight: 600;">Nama Siswa</th>
                                <th style="color: #25671E; font-weight: 600;">NIS</th>
                                <th class="text-center" style="color: #25671E; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendance->details as $detail)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $detail->student->user?->name }}</div>
                                    </td>
                                    <td>{{ $detail->student->nis }}</td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = match($detail->status) {
                                                'hadir' => 'bg-success',
                                                'izin' => 'bg-info text-dark',
                                                'sakit' => 'bg-warning text-dark',
                                                'alpa' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase" style="min-width: 85px;">
                                            {{ $detail->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
