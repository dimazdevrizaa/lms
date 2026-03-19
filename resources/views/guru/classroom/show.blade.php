@extends('layouts.lms')

@section('title', 'Data Siswa - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.index') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">👥 Data Siswa Kelas {{ $class->name }}</h1>
        <p class="text-muted mb-0">Total: <strong>{{ $students->count() }} siswa</strong></p>
    </div>

    @if($students->count() > 0)
        <div class="card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">No.</th>
                                <th style="color: #25671E; font-weight: 600;">🆔 NIS</th>
                                <th style="color: #25671E; font-weight: 600;">👤 Nama</th>
                                <th style="color: #25671E; font-weight: 600;">📞 No. HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: #F2B50B; color: #25671E;">
                                            {{ $student->nis }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: #25671E;">{{ $student->user->name }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $student->phone ?? '—' }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada siswa di kelas ini.
        </div>
    @endif
@endsection
