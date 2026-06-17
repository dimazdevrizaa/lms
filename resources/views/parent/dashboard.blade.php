<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemantauan Orang Tua - {{ $student->user->name }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #25671E;
            --secondary-color: #48A111;
            --accent-color: #F2B50B;
            --light-bg: #F7F0F0;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        .header-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 0 0 30px 30px;
            padding: 40px 0;
            box-shadow: 0 4px 15px rgba(37, 103, 30, 0.15);
        }

        .nav-tabs-custom {
            border-bottom: none;
            gap: 10px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 12px;
            color: #555;
            font-weight: 600;
            padding: 12px 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            background-color: #f8f9fa;
        }

        .nav-tabs-custom .nav-link.active {
            background-color: var(--primary-color);
            color: white !important;
            box-shadow: 0 4px 10px rgba(37, 103, 30, 0.2);
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }

        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        .badge-present { background-color: #48A111; }
        .badge-permitted { background-color: #F2B50B; color: #25671E; }
        .badge-sick { background-color: #F2B50B; color: #25671E; }
        .badge-absent { background-color: #999; }

        .stat-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body>

    <!-- Header Banner -->
    <header class="header-banner mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle p-1 d-none d-sm-block">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                             style="width: 70px; height: 70px; background-color: var(--secondary-color); font-size: 2rem; font-weight: 700;">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark mb-2"><i class="fas fa-child me-1"></i> Profil Siswa Pantauan</span>
                        <h2 class="mb-1 fw-bold text-white">{{ $student->user->name }}</h2>
                        <p class="mb-0 text-white-50">
                            Kelas: <strong>{{ $student->schoolClass?->name ?? '-' }}</strong> | 
                            NIS: <strong>{{ $student->nis }}</strong> | 
                            Tahun Ajaran: <strong>{{ $student->schoolClass?->academicYear?->name ?? '-' }}</strong>
                        </p>
                    </div>
                </div>
                <div>
                    <form action="{{ route('parent.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-light rounded-pill px-4 fw-bold text-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="container mb-5">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show rounded-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Quick Summary Cards -->
        <div class="row mb-4">
            @php
                $totDaily = $dailyAttendances->count();
                $hadirDaily = $dailyAttendances->where('status', 'hadir')->count();
                $presentPct = $totDaily > 0 ? round(($hadirDaily / $totDaily) * 100, 1) : 100;
                
                $completedTasks = $submissions->count();
                $gradedTasks = $submissions->whereNotNull('score')->count();
                $avgScore = $gradedTasks > 0 ? round($submissions->whereNotNull('score')->avg('score')) : '-';
                
                $goodBehaviors = $behaviorRecords->where('type', 'prestasi')->count();
                $badBehaviors = $behaviorRecords->where('type', 'pelanggaran')->count();
            @endphp

            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100" style="border-left: 6px solid #48A111;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted d-block small mb-1">Kehadiran Harian</span>
                            <h3 class="fw-bold mb-0 text-success">{{ $presentPct }}%</h3>
                            <small class="text-muted">{{ $hadirDaily }} dari {{ $totDaily }} hari</small>
                        </div>
                        <i class="fas fa-calendar-check fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100" style="border-left: 6px solid #F2B50B;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted d-block small mb-1">Rata-Rata Nilai Tugas</span>
                            <h3 class="fw-bold mb-0 text-warning" style="color: #25671E !important;">{{ $avgScore }}</h3>
                            <small class="text-muted">{{ $gradedTasks }} tugas dinilai</small>
                        </div>
                        <i class="fas fa-graduation-cap fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100" style="border-left: 6px solid #25671E;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted d-block small mb-1">Catatan Wali Kelas</span>
                            <h3 class="fw-bold mb-0" style="color: #25671E;">{{ $behaviorRecords->count() }}</h3>
                            <small class="text-muted">
                                <span class="text-success">{{ $goodBehaviors }} Prestasi</span> | 
                                <span class="text-danger">{{ $badBehaviors }} Teguran</span>
                            </small>
                        </div>
                        <i class="fas fa-award fa-2x opacity-50" style="color: #25671E;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="parentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance-pane" type="button" role="tab" aria-controls="attendance-pane" aria-selected="true">
                    <i class="fas fa-clipboard-check me-2"></i> Kehadiran Anak
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="grades-tab" data-bs-toggle="tab" data-bs-target="#grades-pane" type="button" role="tab" aria-controls="grades-pane" aria-selected="false">
                    <i class="fas fa-graduation-cap me-2"></i> Tugas & Nilai
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="behavior-tab" data-bs-toggle="tab" data-bs-target="#behavior-pane" type="button" role="tab" aria-controls="behavior-pane" aria-selected="false">
                    <i class="fas fa-award me-2"></i> Catatan Wali Kelas
                </button>
            </li>
        </ul>

        <div class="tab-content" id="parentTabContent">
            
            <!-- Kehadiran Pane -->
            <div class="tab-pane fade show active" id="attendance-pane" role="tabpanel" aria-labelledby="attendance-tab">
                <div class="row">
                    <!-- Daily Attendance (Wali Kelas) -->
                    <div class="col-lg-6 mb-4">
                        <div class="card card-custom h-100">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0" style="color: #25671E;"><i class="fas fa-user-check me-2"></i> Kehadiran Harian Wali Kelas</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dailyAttendances as $da)
                                                <tr>
                                                    <td><strong>{{ \Carbon\Carbon::parse($da->attendance?->date)->format('d M Y') }}</strong></td>
                                                    <td>
                                                        <span class="badge badge-{{ $da->status }}">{{ ucfirst($da->status) }}</span>
                                                    </td>
                                                    <td class="small text-muted">{{ $da->note ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada catatan absensi harian.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subject Attendance -->
                    <div class="col-lg-6 mb-4">
                        <div class="card card-custom h-100">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0" style="color: #25671E;"><i class="fas fa-book-reader me-2"></i> Kehadiran Sesi Mata Pelajaran</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subjectAttendances as $sa)
                                                <tr>
                                                    <td><strong>{{ \Carbon\Carbon::parse($sa->attendance?->date)->format('d M Y') }}</strong></td>
                                                    <td>
                                                        <span class="fw-semibold text-dark">{{ $sa->attendance?->subject?->name }}</span>
                                                        <small class="text-muted d-block">Guru: {{ $sa->attendance?->teacher?->user->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $sa->status }}">{{ ucfirst($sa->status) }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada catatan absensi mata pelajaran.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tugas & Nilai Pane -->
            <div class="tab-pane fade" id="grades-pane" role="tabpanel" aria-labelledby="grades-tab">
                <div class="row">
                    <!-- Assignment Submissions (Tugas & Latihan) -->
                    <div class="col-lg-7 mb-4">
                        <div class="card card-custom h-100">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0" style="color: #25671E;"><i class="fas fa-tasks me-2"></i> Status Pengumpulan Tugas</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama Tugas</th>
                                                <th>Pengumpulan</th>
                                                <th>Nilai / Umpan Balik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($submissions as $sub)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold text-dark">{{ $sub->assignment?->title }}</span>
                                                        <small class="text-muted d-block">{{ $sub->assignment?->subject?->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Dikumpul
                                                        </span>
                                                        <small class="text-muted d-block mt-1">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($sub->score !== null)
                                                            <span class="badge bg-primary fs-6">{{ $sub->score }}</span>
                                                            @if($sub->feedback)
                                                                <small class="text-muted d-block mt-1">"{{ Str::limit($sub->feedback, 50) }}"</small>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dinilai</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada tugas yang dikumpulkan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nilai Rapor oleh Wali Kelas -->
                    <div class="col-lg-5 mb-4">
                        <div class="card card-custom h-100">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0" style="color: #25671E;"><i class="fas fa-file-invoice me-2"></i> Nilai Akademik Terinput</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Mapel</th>
                                                <th>Jenis Nilai</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($grades as $grade)
                                                <tr>
                                                    <td><strong>{{ $grade->subject?->name }}</strong></td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ ucfirst($grade->assessment_type) }}</span>
                                                        <small class="text-muted d-block mt-1">{{ \Carbon\Carbon::parse($grade->assessment_date)->format('d/m/Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary fs-6">{{ $grade->score }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada nilai terinput dari wali kelas.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Perilaku Pane -->
            <div class="tab-pane fade" id="behavior-pane" role="tabpanel" aria-labelledby="behavior-tab">
                <div class="card card-custom">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0" style="color: #25671E;"><i class="fas fa-star me-2"></i> Catatan Perilaku & Karakter Siswa</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            @forelse($behaviorRecords as $br)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 p-3" 
                                         style="border: none; border-radius: 12px; 
                                                background-color: {{ $br->type === 'prestasi' ? '#eef9ec' : '#fdf2f2' }}; 
                                                border-left: 5px solid {{ $br->type === 'prestasi' ? '#48A111' : '#dc3545' }};">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0" style="color: {{ $br->type === 'prestasi' ? '#25671E' : '#b02a37' }};">
                                                {{ $br->title }}
                                            </h6>
                                            <span class="badge" style="background-color: {{ $br->type === 'prestasi' ? '#48A111' : '#dc3545' }};">
                                                {{ ucfirst($br->type) }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-2">{{ $br->description }}</p>
                                        <small class="text-black-50"><i class="fas fa-calendar-alt me-1"></i> Tanggal Kejadian: {{ \Carbon\Carbon::parse($br->date)->format('d M Y') }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="fas fa-heart fa-3x mb-3 text-light"></i>
                                    <h6 class="mb-0">Tidak ada catatan khusus. Perilaku siswa baik dan kondusif.</h6>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center py-4 text-muted small mt-5 border-top">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} LMS SMA Negeri 15 Padang. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
