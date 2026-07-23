@extends('layouts.lms')

@section('title', 'Rekap Nilai - ' . $class->name)

@section('content')
    <div class="page-header-banner reveal">
        <div class="page-header-banner-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif;">📊 Rekap Nilai Siswa</h1>
                    <p>Kelas {{ $class->name }} • Lihat ringkasan nilai dan statistik siswa</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('guru.classroom.index') }}" class="btn btn-outline-light d-inline-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('guru.classroom.grades.input', $class) }}" class="btn btn-light d-inline-flex align-items-center gap-2" style="color: var(--primary) !important; font-weight: 700;">
                        <i class="fas fa-plus"></i> Input Nilai Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai Siswa -->
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-list-ol"></i>
            </div>
            <h5 class="content-card-title">Ringkasan Nilai Kelas</h5>
        </div>
        <div class="content-card-body p-0">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 80px;">No.</th>
                                <th>📝 Nama Siswa</th>
                                <th class="text-center" style="width: 200px;">Rata-Rata Nilai</th>
                                <th class="text-center" style="width: 200px;">Total Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @php
                                    $grades = $student->grades->pluck('score')->toArray();
                                    $avgGrade = count($grades) > 0 ? number_format(array_sum($grades) / count($grades), 2) : '—';
                                    $totalGrades = count($grades);
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->user->name }}</div>
                                        <div class="text-muted small">NISN: {{ $student->nisn }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($avgGrade !== '—')
                                            @php
                                                $badgeClass = $avgGrade >= 80 ? 'grade-badge--high' : ($avgGrade >= 70 ? 'grade-badge--mid' : 'grade-badge--low');
                                            @endphp
                                            <span class="badge grade-badge {{ $badgeClass }}">
                                                {{ $avgGrade }}
                                            </span>
                                        @else
                                            <span class="text-muted fw-semibold">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-success">{{ $totalGrades }} kali</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <h5 class="empty-state-text mt-3">Belum Ada Siswa</h5>
                    <p class="text-muted mb-0">Tidak ada siswa yang terdaftar di kelas perwalian Anda saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .grade-badge {
            font-size: 0.9rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 6px 14px;
            border-radius: var(--radius-sm);
        }
        .grade-badge--high {
            background-color: rgba(67, 160, 71, 0.12);
            color: #2E7D32;
            border: 1px solid rgba(67, 160, 71, 0.2);
        }
        .grade-badge--mid {
            background-color: rgba(249, 168, 37, 0.12);
            color: #B26A00;
            border: 1px solid rgba(249, 168, 37, 0.2);
        }
        .grade-badge--low {
            background-color: rgba(198, 40, 40, 0.1);
            color: #C62828;
            border: 1px solid rgba(198, 40, 40, 0.2);
        }
    </style>
    @endpush
@endsection
