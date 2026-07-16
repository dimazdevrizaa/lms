@extends('layouts.lms')

@section('title', 'Kelas Saya')

@section('content')
    <!-- Header Banner -->
    <div class="header-banner mb-5 p-4 rounded-4 text-white d-flex align-items-center justify-content-between reveal" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); box-shadow: 0 4px 24px rgba(37, 103, 30, 0.12); border-radius: 20px;">
        <div>
            <h1 class="h2 mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;">📚 Wali Kelas</h1>
            <p class="mb-0 text-white-50">Kelola informasi data siswa, kehadiran, rekap nilai, dan catatan perilaku kelas Anda</p>
        </div>
    </div>

    @if($classes->count() > 0)
        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4 mb-4 reveal">
                    <div class="content-card h-100 border-0 shadow-sm classroom-card" style="border-top: 4px solid var(--primary); transition: all 0.3s cubic-bezier(0.22, 0.61, 0.36, 1);">
                        <div class="content-card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(27, 94, 32, 0.06); width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-graduation-cap text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ $class->name }}</h5>
                                    <small class="text-muted">Tingkat Kelas: <strong>{{ $class->level ?? '—' }}</strong></small>
                                </div>
                            </div>

                            <div class="mt-4 d-flex flex-column gap-2">
                                <a href="{{ route('guru.classroom.show', $class) }}" class="btn btn-outline-primary-theme w-100 d-flex align-items-center justify-content-between px-3 py-2" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none;">
                                    <span>👥 Data Siswa</span> <i class="fas fa-chevron-right small opacity-50"></i>
                                </a>
                                <a href="{{ route('guru.classroom.attendance', $class) }}" class="btn btn-outline-secondary-theme w-100 d-flex align-items-center justify-content-between px-3 py-2" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none;">
                                    <span>📋 Kehadiran & Absensi</span> <i class="fas fa-chevron-right small opacity-50"></i>
                                </a>
                                <a href="{{ route('guru.classroom.behavior', $class) }}" class="btn btn-outline-accent-theme w-100 d-flex align-items-center justify-content-between px-3 py-2" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none;">
                                    <span>📝 Catatan Perilaku</span> <i class="fas fa-chevron-right small opacity-50"></i>
                                </a>
                                <a href="{{ route('guru.classroom.grades', $class) }}" class="btn btn-outline-primary-theme w-100 d-flex align-items-center justify-content-between px-3 py-2" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; border-color: var(--primary); color: var(--primary); text-decoration: none;">
                                    <span>📊 Rekap Nilai Siswa</span> <i class="fas fa-chevron-right small opacity-50"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info border-0 shadow-sm p-4 reveal" role="alert" style="border-radius: 12px; background-color: rgba(67, 160, 71, 0.08); color: var(--primary);">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-info-circle fa-2x"></i>
                <div>
                    <h5 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Belum Menjadi Wali Kelas</h5>
                    <p class="mb-0 small text-muted">Anda belum terdaftar sebagai wali kelas untuk kelas manapun saat ini.</p>
                </div>
            </div>
        </div>
    @endif

    <style>
        .classroom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(37, 103, 30, 0.08) !important;
        }
    </style>
@endsection
