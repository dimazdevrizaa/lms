@extends('layouts.lms')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="mb-0">📊 Dashboard Admin</h1>
        <div class="text-muted small">
            {{ date('l, d F Y') }}
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="row mb-5">
        <!-- Total User Card -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">👥 Total User</h5>
                            <p class="display-5" style="color: #F2B50B; font-weight: 700; margin-bottom: 0;">{{ $userCount }}</p>
                            <small class="text-muted">Pengguna terdaftar</small>
                        </div>
                        <div class="text-center" style="opacity: 0.1; font-size: 2rem;">👥</div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Total Guru Card -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">🎓 Total Guru</h5>
                            <p class="display-5" style="color: #48A111; font-weight: 700; margin-bottom: 0;">{{ $teacherCount }}</p>
                            <small class="text-muted">Guru aktif</small>
                        </div>
                        <div class="text-center" style="opacity: 0.1; font-size: 2rem;">🎓</div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Total Siswa Card -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">🎒 Total Siswa</h5>
                            <p class="display-5" style="color: #25671E; font-weight: 700; margin-bottom: 0;">{{ $studentCount }}</p>
                            <small class="text-muted">Siswa terdaftar</small>
                        </div>
                        <div class="text-center" style="opacity: 0.1; font-size: 2rem;">🎒</div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Total Kelas Card -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">🏫 Total Kelas</h5>
                            <p class="display-5" style="color: #0d6efd; font-weight: 700; margin-bottom: 0;">{{ $classCount }}</p>
                            <small class="text-muted">Kelas aktif</small>
                        </div>
                        <div class="text-center" style="opacity: 0.1; font-size: 2rem;">🏫</div>
                    </div>
                </div>
                <a href="{{ route('admin.classes.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">⚙️ Aksi Cepat</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                <span>👤 Kelola User</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-success w-100">
                                <span>🎓 Kelola Kelas</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-warning w-100">
                                <span>📅 Tahun Ajaran</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.monitoring.index') }}" class="btn btn-outline-info w-100">
                                <span>📊 Monitoring</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection