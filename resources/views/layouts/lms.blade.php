<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LMS SMA 15 Padang')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- TomSelect CSS for beautiful dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #25671E;
            --secondary-color: #48A111;
            --accent-color: #F2B50B;
            --light-color: #F7F0F0;
            --sidebar-width: 260px;
            --sidebar-width-mobile: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--light-color) !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, .navbar-brand, .sidebar-header {
            font-family: 'Outfit', sans-serif;
        }

        /* Top Navbar */
        .navbar {
            background: rgba(37, 103, 30, 0.95) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 200ms ease-in-out;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .navbar-text {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
        }

        .btn-outline-light {
            color: rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 0.9);
        }

        .btn-outline-light:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 56px;
            width: var(--sidebar-width);
            height: calc(100vh - 56px);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1rem 0;
        }

        .sidebar-nav li {
            margin: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0.25rem 1rem;
            border-radius: 12px;
        }

        .sidebar-nav a:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 103, 30, 0.2);
        }

        .sidebar-nav i {
            width: 1.5rem;
            text-align: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .sidebar-header {
            padding: 2rem 2.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Main content wrapper */
        .main-wrapper {
            display: flex;
            flex: 1;
            margin-top: 56px;
        }

        .sidebar-placeholder {
            width: var(--sidebar-width);
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
            padding: 2.5rem;
            overflow-y: auto;
            position: relative;
            z-index: 1;
        }

        /* Background Watermark Logo */
        body::before {
            content: "";
            position: fixed;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px; 
            height: 350px;
            background-image: url('{{ asset("images/logo.jpg") }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            opacity: 0.05;
            z-index: -999;
            pointer-events: none;
        }

        /* Toggle button */
        .sidebar-toggle {
            display: none;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            border-radius: 4px;
            margin-right: 1rem;
        }

        .sidebar-toggle:hover {
            background-color: var(--secondary-color);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem !important;
            }

            .sidebar-toggle {
                display: block;
            }

            .sidebar-placeholder {
                display: none;
            }

            .sidebar {
                width: 100%;
                max-width: 280px;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                padding: 1.5rem;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-text {
                display: none;
            }
        }

        /* General styles */
        h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
        }

        /* Anti-Cheat: Disable text selection */
        .anti-cheat-active {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .allow-copy, .allow-copy * {
            user-select: text !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
        }

        h2, h3, h4, h5, h6 {
            color: var(--primary-color);
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            cursor: pointer;
        }

        .card-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.25rem;
            font-family: 'Outfit', sans-serif;
        }

        .card-body .display-5 {
            color: var(--primary-color);
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -1px;
        }

        .alert-success {
            background-color: rgba(72, 161, 17, 0.1);
            border-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            border-color: #DC2626;
            color: #991B1B;
        }

        .table {
            color: var(--primary-color);
        }

        .table thead {
            background-color: rgba(37, 103, 30, 0.05);
            border-bottom: 2px solid var(--primary-color);
        }

        .table thead th {
            color: var(--primary-color);
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .badge {
            background-color: var(--secondary-color) !important;
        }

        .badge-primary {
            background-color: var(--primary-color) !important;
        }

        .badge-secondary {
            background-color: var(--secondary-color) !important;
        }

        .badge-warning {
            background-color: var(--accent-color) !important;
            color: var(--primary-color) !important;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Batasi ukuran ikon panah agar tidak membesar */
        .fa-arrow-left, .fa-arrow-right, .fa-chevron-left, .fa-chevron-right {
            font-size: 0.875em !important;
        }

        /* Shimmer effect for skeleton loading */
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        .skeleton-wrapper {
            display: none;
            flex-direction: column;
            gap: 1.5rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Page Transition States */
        body.page-loading .main-content > *:not(#skeleton-loader) {
            display: none !important;
        }

        body.page-loading .skeleton-wrapper {
            display: flex !important;
        }

        .skeleton-item {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite linear;
            border-radius: 8px;
        }

        /* Specific skeleton structures */
        .skeleton-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
        }

        .skeleton-title {
            width: 60%;
            height: 32px;
            margin-bottom: 0.5rem;
        }

        .skeleton-subtitle {
            width: 35%;
            height: 20px;
        }

        .skeleton-card {
            height: 200px;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .skeleton-line {
            height: 16px;
            margin-bottom: 0.75rem;
            border-radius: 4px;
        }

        .skeleton-line.w-80 { width: 80%; }
        .skeleton-line.w-70 { width: 70%; }
        .skeleton-line.w-90 { width: 90%; }
        .skeleton-line.w-50 { width: 50%; }
        .skeleton-line.w-30 { width: 30%; }

        .skeleton-table-row {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="d-flex align-items-center flex-grow-1">
            <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <span style="color: var(--accent-color); margin-right: 0.5rem;">📚</span>
                LMS SMA 15 Padang
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @guest
                <a class="nav-link ms-2" href="{{ route('login') }}">Login</a>
            @else
                <span class="navbar-text me-2">
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
                </form>
            @endguest
        </div>
    </nav>

    <!-- Sidebar -->
    @auth
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-nav">
                @if(auth()->user()->role === 'admin')
                    <li class="sidebar-header">Utama</li>
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    
                    <li class="sidebar-header">Manajemen User</li>
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Data User</a></li>
                    
                    <li class="sidebar-header">Akademik</li>
                    <li><a href="{{ route('admin.academic-years.index') }}" class="{{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}"><i class="fas fa-calendar"></i> Tahun Ajaran</a></li>
                    <li><a href="{{ route('admin.classes.index') }}" class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}"><i class="fas fa-door-open"></i> Data Kelas</a></li>
                    <li><a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}"><i class="fas fa-book-open"></i> Mata Pelajaran</a></li>
                    
                    <li class="sidebar-header">Sistem</li>
                    <li><a href="{{ route('admin.monitoring.index') }}" class="{{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}"><i class="fas fa-eye"></i> Monitoring</a></li>

                @elseif(auth()->user()->role === 'tatausaha')
                    <li class="sidebar-header">Utama</li>
                    <li><a href="{{ route('tatausaha.dashboard') }}" class="{{ request()->routeIs('tatausaha.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    
                    <li class="sidebar-header">Master Data</li>
                    <li><a href="{{ route('tatausaha.students.index') }}" class="{{ request()->routeIs('tatausaha.students.*') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Data Siswa</a></li>
                    <li><a href="{{ route('tatausaha.teachers.index') }}" class="{{ request()->routeIs('tatausaha.teachers.*') ? 'active' : '' }}"><i class="fas fa-chalkboard-user"></i> Data Guru</a></li>
                    <li><a href="{{ route('tatausaha.teaching-assignments.index') }}" class="{{ request()->routeIs('tatausaha.teaching-assignments.*') ? 'active' : '' }}"><i class="fas fa-chalkboard"></i> Penugasan Guru</a></li>
                    
                    <li class="sidebar-header">Penjadwalan</li>
                    <li><a href="{{ route('tatausaha.schedules.index') }}" class="{{ request()->routeIs('tatausaha.schedules.*') ? 'active' : '' }}"><i class="fas fa-calendar-week"></i> Jadwal Pelajaran</a></li>
                    
                    <li class="sidebar-header">Laporan</li>
                    <li><a href="{{ route('tatausaha.reports.index') }}" class="{{ request()->routeIs('tatausaha.reports.*') ? 'active' : '' }}"><i class="fas fa-file-pdf"></i> Laporan Akademik</a></li>

                @elseif(auth()->user()->role === 'guru')
                    <li class="sidebar-header">Utama</li>
                    <li><a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    
                    <li class="sidebar-header">Kegiatan Mengajar</li>
                    <li><a href="{{ route('guru.meetings.index') }}" class="{{ request()->routeIs('guru.meetings.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Pertemuan Kelas</a></li>
                    <li><a href="{{ route('guru.materials.index') }}" class="{{ request()->routeIs('guru.materials.*') ? 'active' : '' }}"><i class="fas fa-book"></i> Materi Ajar</a></li>
                    <li><a href="{{ route('guru.assignments.index') }}" class="{{ request()->routeIs('guru.assignments.index') || request()->routeIs('guru.assignments.create') || request()->routeIs('guru.assignments.edit') || request()->routeIs('guru.assignments.show') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Tugas & Latihan</a></li>
                    <li>
                        <a href="{{ route('guru.assignments.grading') }}" class="{{ request()->routeIs('guru.assignments.grading') ? 'active' : '' }}">
                            <i class="fas fa-check-double"></i> Penilaian
                            @php
                                $pendingCount = \App\Models\AssignmentSubmission::whereHas('assignment', function($q) {
                                    $q->where('teacher_id', auth()->user()->teacher?->id);
                                })->whereNull('score')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge rounded-pill ms-auto" style="background-color: #dc3545; font-size: 0.65rem;">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{ route('guru.attendances.index') }}" class="{{ request()->routeIs('guru.attendances.*') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> Absensi</a></li>

                    @if(auth()->user()->teacher && auth()->user()->teacher->homeroomClasses()->count() > 0)
                        <li class="sidebar-header">Wali Kelas</li>
                        <li><a href="{{ route('guru.classroom.index') }}" class="{{ request()->routeIs('guru.classroom.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Kelola Kelas Perwalian</a></li>
                    @endif

                @elseif(auth()->user()->role === 'siswa')
                    <li class="sidebar-header">Utama</li>
                    <li><a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    
                    <li class="sidebar-header">Akademik</li>
                    <li><a href="{{ route('siswa.subjects.index') }}" class="{{ request()->routeIs('siswa.subjects.*') || request()->routeIs('siswa.meetings.*') ? 'active' : '' }}"><i class="fas fa-book-open"></i> Mata Pelajaran</a></li>
                    <li><a href="{{ route('siswa.attendance.index') }}" class="{{ request()->routeIs('siswa.attendance.*') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> Riwayat Kehadiran</a></li>
                @endif
            </ul>
        </aside>
    @endauth

    <!-- Main Content -->
    <div class="main-wrapper">
        @auth
            <div class="sidebar-placeholder"></div>
        @endauth
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-2"></i>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Skeleton Page Transition Loader -->
            <div id="skeleton-loader" class="skeleton-wrapper">
                <!-- Header Skeleton -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="skeleton-item skeleton-circle"></div>
                    <div class="flex-grow-1">
                        <div class="skeleton-item skeleton-title"></div>
                        <div class="skeleton-item skeleton-subtitle"></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Card Skeleton -->
                        <div class="skeleton-item skeleton-card"></div>
                        
                        <!-- Lines Skeletons -->
                        <div class="card p-4 border-0 shadow-sm mb-4">
                            <div class="skeleton-item skeleton-line w-90"></div>
                            <div class="skeleton-item skeleton-line w-80"></div>
                            <div class="skeleton-item skeleton-line w-70"></div>
                            <div class="skeleton-item skeleton-line w-50"></div>
                        </div>
                        
                        <!-- Table/Row Skeletons -->
                        <div class="card p-4 border-0 shadow-sm">
                            <div class="skeleton-table-row">
                                <div class="skeleton-item skeleton-circle" style="width: 32px; height: 32px;"></div>
                                <div class="flex-grow-1 d-flex flex-column gap-2 justify-content-center">
                                    <div class="skeleton-item skeleton-line w-30" style="margin-bottom: 0;"></div>
                                    <div class="skeleton-item skeleton-line w-50" style="margin-bottom: 0;"></div>
                                </div>
                            </div>
                            <div class="skeleton-table-row">
                                <div class="skeleton-item skeleton-circle" style="width: 32px; height: 32px;"></div>
                                <div class="flex-grow-1 d-flex flex-column gap-2 justify-content-center">
                                    <div class="skeleton-item skeleton-line w-30" style="margin-bottom: 0;"></div>
                                    <div class="skeleton-item skeleton-line w-50" style="margin-bottom: 0;"></div>
                                </div>
                            </div>
                            <div class="skeleton-table-row border-0">
                                <div class="skeleton-item skeleton-circle" style="width: 32px; height: 32px;"></div>
                                <div class="flex-grow-1 d-flex flex-column gap-2 justify-content-center">
                                    <div class="skeleton-item skeleton-line w-30" style="margin-bottom: 0;"></div>
                                    <div class="skeleton-item skeleton-line w-50" style="margin-bottom: 0;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Sidebar Card Skeleton -->
                        <div class="card p-4 border-0 shadow-sm mb-4">
                            <div class="skeleton-item skeleton-title w-50 mb-3" style="height: 24px;"></div>
                            <div class="skeleton-item skeleton-line w-90"></div>
                            <div class="skeleton-item skeleton-line w-80"></div>
                            <div class="skeleton-item skeleton-line w-90"></div>
                            <div class="skeleton-item skeleton-line w-70"></div>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        // Inisialisasi TomSelect pada semua elemen yang memiliki class .ts-select
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.ts-select').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        });

        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Close sidebar on link click (mobile)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                    }
                });
            });
        }
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply unselectable class to body for global anti-cheat
            document.body.classList.add('anti-cheat-active');

            // Block Right Click
            document.addEventListener('contextmenu', event => event.preventDefault());

            // Block Copy, Cut, Paste
            ['copy', 'cut', 'paste'].forEach(function(eventType) {
                document.addEventListener(eventType, function(e) {
                    if (e.target && (
                        e.target.tagName === 'INPUT' || 
                        e.target.tagName === 'TEXTAREA' || 
                        e.target.closest('.allow-copy') || 
                        window.copyAllowed === true
                    )) {
                        return;
                    }
                    e.preventDefault();
                });
            });

            // Block PrintScreen and common dev tools shortcuts
            document.addEventListener('keyup', function(e) {
                if (e.key === 'PrintScreen' || e.keyCode === 44) {
                    navigator.clipboard.writeText(''); // Clear clipboard
                    alert('Screenshot dan Print Screen telah dinonaktifkan oleh sistem.');
                }
            });

            document.addEventListener('keydown', function(e) {
                // Block F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P
                if (e.key === 'F12' || 
                   (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'i' || e.key === 'j')) || 
                   (e.ctrlKey && (e.key === 'U' || e.key === 'u' || e.key === 'S' || e.key === 's' || e.key === 'P' || e.key === 'p'))) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <!-- Skeleton Page Transition Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show skeleton loading animation during page transitions
            window.addEventListener('beforeunload', function () {
                document.body.classList.add('page-loading');
            });

            // Handle page show (e.g. from back-forward cache) to hide loading state
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    document.body.classList.remove('page-loading');
                }
            });

            // Intercept form submissions
            document.querySelectorAll('form').forEach(form => {
                const target = form.getAttribute('target');
                if (target !== '_blank') {
                    form.addEventListener('submit', function () {
                        // Small delay to allow HTML5 validation check to run first
                        setTimeout(() => {
                            if (form.checkValidity()) {
                                document.body.classList.add('page-loading');
                            }
                        }, 50);
                    });
                }
            });

            // Intercept links to show loading immediately
            document.querySelectorAll('a').forEach(link => {
                const href = link.getAttribute('href');
                const target = link.getAttribute('target');
                if (href && 
                    !href.startsWith('#') && 
                    !href.startsWith('javascript:') && 
                    !href.startsWith('mailto:') && 
                    !href.startsWith('tel:') && 
                    target !== '_blank' && 
                    !link.hasAttribute('download') &&
                    !link.classList.contains('no-loader')) {
                    
                    link.addEventListener('click', function (e) {
                        // Only intercept left clicks without modifier keys
                        if (e.button === 0 && !e.ctrlKey && !e.metaKey && !e.shiftKey && !e.altKey) {
                            document.body.classList.add('page-loading');
                        }
                    });
                }
            });
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>
</html>

