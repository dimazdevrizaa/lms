@extends('layouts.lms')

@section('title', 'Detail Kehadiran')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">🔍 Detail Kehadiran</h1>
            <p class="text-muted mb-0">Informasi lengkap kehadiran siswa pada sesi ini</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('guru.attendances.index') }}">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Info Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #25671E !important;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4" style="color: #25671E;">Informasi Kelas</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block">Tanggal</label>
                        <span class="fw-bold">{{ \Carbon\Carbon::parse($attendance->date)->format('d F Y') }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Kelas</label>
                        <span class="badge" style="background-color: #48A111;">{{ $attendance->schoolClass?->name }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Mata Pelajaran</label>
                        <span class="fw-bold text-dark">{{ $attendance->subject?->name }}</span>
                    </div>

                    @if($attendance->meeting_id)
                    <div class="mb-3">
                        <label class="text-muted small d-block">Pertemuan</label>
                        <a href="{{ route('guru.meetings.show', $attendance->meeting_id) }}" class="fw-bold text-success" style="text-decoration: none;">
                            <i class="fas fa-calendar-check me-1"></i> Pertemuan Ke-{{ $attendance->meeting->number }}
                        </a>
                    </div>
                    @endif

                    @php
                        $stats = $attendance->details->groupBy('status')->map->count();
                    @endphp

                    <div class="mt-4 pt-3 border-top">
                        <label class="text-muted small d-block mb-2">Ringkasan:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-success">Hadir: {{ $stats['hadir'] ?? 0 }}</span>
                            <span class="badge bg-info text-dark">Izin: {{ $stats['izin'] ?? 0 }}</span>
                            <span class="badge bg-warning text-dark">Sakit: {{ $stats['sakit'] ?? 0 }}</span>
                            <span class="badge bg-danger">Alpa: {{ $stats['alpa'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #F7F0F0;">
                            <tr>
                                <th class="ps-4" style="color: #25671E;">Nama Siswa</th>
                                <th>NIS</th>
                                <th class="text-center">Status</th>
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
                                        <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase" style="min-width: 80px;">
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
