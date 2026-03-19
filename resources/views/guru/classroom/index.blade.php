@extends('layouts.lms')

@section('title', 'Kelas Saya')

@section('content')
    <div class="mb-5">
        <h1 class="h3 mb-2">📚 Kelola Kelas</h1>
        <p class="text-muted mb-0">Anda adalah wali kelas untuk kelas-kelas berikut</p>
    </div>

    @if($classes->count() > 0)
        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-top: 4px solid #48A111;">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #25671E; font-weight: 600;">📖 {{ $class->name }}</h5>
                            <p class="card-text text-muted small mb-3">
                                Level: <strong>{{ $class->level ?? '—' }}</strong>
                            </p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('guru.classroom.show', $class) }}" class="btn btn-sm btn-outline-primary">
                                    👥 Data Siswa
                                </a>
                                <a href="{{ route('guru.classroom.attendance', $class) }}" class="btn btn-sm btn-outline-info">
                                    📋 Absensi Kelas
                                </a>
                                <a href="{{ route('guru.classroom.behavior', $class) }}" class="btn btn-sm btn-outline-warning">
                                    📝 Catatan Perilaku
                                </a>
                                <a href="{{ route('guru.classroom.grades', $class) }}" class="btn btn-sm btn-outline-success">
                                    📊 Rekap Nilai
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Anda belum menjadi wali kelas untuk kelas manapun.
        </div>
    @endif
@endsection
