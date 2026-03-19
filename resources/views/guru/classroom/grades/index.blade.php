@extends('layouts.lms')

@section('title', 'Rekap Nilai - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.index') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">📊 Rekap Nilai Siswa - {{ $class->name }}</h1>
        <p class="text-muted mb-0">Lihat ringkasan nilai siswa</p>
    </div>

    <!-- Tombol Navigation -->
    <div class="mb-4">
        <a href="{{ route('guru.classroom.grades.input', $class) }}" class="btn btn-lg" style="background-color: #48A111; color: white; border: none;">
            ➕ Input Nilai
        </a>
    </div>

    <!-- Tabel Nilai Siswa -->
    <div class="card">
        <div class="card-body p-4">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">No.</th>
                                <th style="color: #25671E; font-weight: 600;">📝 Nama Siswa</th>
                                <th style="color: #25671E; font-weight: 600; text-align: center;">Rata-Rata Nilai</th>
                                <th style="color: #25671E; font-weight: 600; text-align: center;">Total Penilaian</th>
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
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <strong style="color: #25671E;">{{ $student->user->name }}</strong><br>
                                        <small class="text-muted">NIS: {{ $student->nis }}</small>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($avgGrade !== '—')
                                            <span class="badge " style="background-color: {{ $avgGrade >= 80 ? '#48A111' : ($avgGrade >= 70 ? '#F2B50B' : '#FF6B6B') }}; color: white; font-size: 1rem; padding: 0.5rem 1rem;">
                                                {{ $avgGrade }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <small style="color: #25671E; font-weight: 600;">{{ $totalGrades }} nilai</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">📭 Belum ada siswa di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
