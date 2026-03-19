@extends('layouts.lms')

@section('title', 'Absensi Kelas - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.index') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">📋 Absensi Harian Kelas {{ $class->name }}</h1>
        <p class="text-muted mb-0">Kelola absensi siswa</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tombol Tambah Absensi -->
    <div class="mb-4">
        <a href="{{ route('guru.classroom.attendance.create', $class) }}" class="btn btn-lg" style="background-color: #48A111; color: white; border: none;">
            ➕ Input Absensi Baru
        </a>
    </div>

    <!-- Daftar Absensi -->
    <div class="card">
        <div class="card-body p-4">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">📅 Tanggal</th>
                                <th style="color: #25671E; font-weight: 600;">✓ Hadir</th>
                                <th style="color: #25671E; font-weight: 600;">📋 Izin</th>
                                <th style="color: #25671E; font-weight: 600;">🏥 Sakit</th>
                                <th style="color: #25671E; font-weight: 600;">❌ Alpa</th>
                                <th class="text-center" style="color: #25671E; font-weight: 600;">Aksi</th>
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
                                        <strong style="color: #25671E;">{{ $attendance->date->translatedFormat('d F Y') }}</strong>
                                    </td>
                                    <td><span style="color: #48A111; font-weight: 600;">{{ $hadir }}</span></td>
                                    <td><span style="color: #F2B50B; font-weight: 600;">{{ $izin }}</span></td>
                                    <td><span style="color: #FF6B6B; font-weight: 600;">{{ $sakit }}</span></td>
                                    <td><span style="color: #FF0000; font-weight: 600;">{{ $alpa }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('guru.classroom.attendance.show', [$class, $attendance]) }}" class="btn btn-sm btn-outline-primary">
                                            👁️ Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">📭 Belum ada data absensi.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
