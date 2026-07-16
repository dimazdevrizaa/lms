@extends('layouts.lms')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Header Banner -->
    <div class="content-card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--secondary) 100%); border: none; margin-bottom: 32px;">
        <div class="content-card-body" style="padding: 36px 32px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2" style="color: #FFFFFF !important; font-size: 1.75rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                        📊 Dashboard Admin
                    </h1>
                    <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 0.95rem;">
                        Selamat datang! Pantau dan kelola sistem LMS dari sini.
                    </p>
                </div>
                <div style="color: rgba(255,255,255,0.7); font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500;">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="stats-grid" style="margin-bottom: 40px;">
        <!-- Total User Card -->
        <a href="{{ route('admin.users.index') }}" class="stat-card stat-card--grades" style="text-decoration: none; color: inherit; display: block;">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon-circle stat-icon-circle--gold">👥</div>
                <div>
                    <div class="stat-label">Total User</div>
                    <div class="stat-value" style="color: var(--accent);">{{ $userCount }}</div>
                    <div class="stat-sub">Pengguna terdaftar</div>
                </div>
            </div>
        </a>

        <!-- Total Guru Card -->
        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="stat-card stat-card--attendance" style="text-decoration: none; color: inherit; display: block;">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon-circle stat-icon-circle--green">🎓</div>
                <div>
                    <div class="stat-label">Total Guru</div>
                    <div class="stat-value stat-value--green">{{ $teacherCount }}</div>
                    <div class="stat-sub">Guru aktif</div>
                </div>
            </div>
        </a>

        <!-- Total Siswa Card -->
        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="stat-card stat-card--behavior" style="text-decoration: none; color: inherit; display: block;">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon-circle stat-icon-circle--deep">🎒</div>
                <div>
                    <div class="stat-label">Total Siswa</div>
                    <div class="stat-value stat-value--primary">{{ $studentCount }}</div>
                    <div class="stat-sub">Siswa terdaftar</div>
                </div>
            </div>
        </a>

        <!-- Total Kelas Card -->
        <a href="{{ route('admin.classes.index') }}" class="stat-card stat-card--attendance" style="text-decoration: none; color: inherit; display: block;">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon-circle stat-icon-circle--green">🏫</div>
                <div>
                    <div class="stat-label">Total Kelas</div>
                    <div class="stat-value stat-value--green">{{ $classCount }}</div>
                    <div class="stat-sub">Kelas aktif</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Quick Actions Section -->
    <div class="content-card">
        <div class="content-card-header">
            <div class="content-card-header-icon">⚙️</div>
            <h5 class="content-card-title">Aksi Cepat</h5>
        </div>
        <div class="content-card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary-theme w-100 py-3">
                        👤 Kelola User
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary-theme w-100 py-3">
                        🎓 Kelola Kelas
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-accent-theme w-100 py-3">
                        📅 Tahun Ajaran
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.monitoring.index') }}" class="btn btn-outline-primary-theme w-100 py-3">
                        📊 Monitoring
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection