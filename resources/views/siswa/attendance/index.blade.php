@extends('layouts.lms')

@section('title', 'Absensi')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">✅ Rekam Kehadiran Saya</h1>
        <p class="text-muted mb-0">Pantau catatan kehadiran Anda di setiap kelas</p>
    </div>

    @if($records->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <h5 class="mb-3">📍 Belum ada data kehadiran</h5>
                <p class="text-muted mb-0">Data kehadiran Anda akan muncul di sini setelah guru mencatat kehadiran di kelas.</p>
            </div>
        </div>
    @else
        <!-- Statistics -->
        <div class="row mb-5">
            @php
                $total = $records->count();
                $hadir = $records->where('status', 'hadir')->count();
                $izin = $records->where('status', 'izin')->count();
                $sakit = $records->where('status', 'sakit')->count();
                $alpa = $records->where('status', 'alpa')->count();
                $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card h-100" style="border-top: 4px solid #48A111;">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Total Kehadiran</h6>
                        <p class="display-5" style="color: #48A111; font-weight: 700;">{{ $total }}</p>
                        <small class="text-muted">Catatan tercatat</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card h-100" style="border-top: 4px solid #25671E;">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Persentase Hadir</h6>
                        <p class="display-5" style="color: #25671E; font-weight: 700;">{{ $persentase }}%</p>
                        <small class="text-muted">Dari total kehadiran</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0" style="border-top: 4px solid #F2B50B;">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Izin/Sakit</h6>
                        <p class="display-5" style="color: #F2B50B; font-weight: 700;">{{ $izin + $sakit }}</p>
                        <small class="text-muted">Izin & Sakit</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card h-100" style="border-top: 4px solid #ccc;">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Alpa</h6>
                        <p class="display-5" style="color: #666; font-weight: 700;">{{ $alpa }}</p>
                        <small class="text-muted">Ketidakhadiran</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #F7F0F0;">
                    <tr>
                        <th style="border-left: 4px solid #25671E; color: #25671E;">📅 Tanggal</th>
                        <th>Status Kehadiran</th>
                        <th>📖 Mata Pelajaran</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>
                                <strong style="color: #25671E;">{{ \Carbon\Carbon::parse($r->attendance?->date ?? now())->format('d M Y') }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($r->status) {
                                        'hadir' => '#48A111',
                                        'izin' => '#F2B50B',
                                        'sakit' => '#F2B50B',
                                        'alpa' => '#999',
                                        default => '#ccc'
                                    };
                                    $statusIcon = match($r->status) {
                                        'hadir' => '✓',
                                        'izin' => '📄',
                                        'sakit' => '🤒',
                                        'alpa' => '✗',
                                        default => '-'
                                    };
                                @endphp
                                <span class="badge" style="background-color: {{ $statusClass }};">{{ $statusIcon }} {{ ucfirst($r->status) }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $r->attendance?->subject_id ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $records->links() }}</div>
    @endif
@endsection

