@extends('layouts.lms')

@section('title', 'Dashboard Siswa')

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-2">👋 Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-muted mb-0">Pantau progres belajar Anda di sini</p>
        </div>
        <div class="text-end">
            <div class="text-muted small">
                {{ date('l, d F Y') }}
            </div>
            @if(Auth::user()->student && Auth::user()->student->schoolClass)
                <div class="mt-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        {{ Auth::user()->student->schoolClass->name }}
                    </span>
                    <span class="badge {{ Auth::user()->student->schoolClass->major == 'IPA' ? 'bg-primary' : (Auth::user()->student->schoolClass->major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                        JURUSAN {{ Auth::user()->student->schoolClass->major ?? 'Umum' }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="row mb-5">
        <!-- Tugas Terserah -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">📋 Tugas Disubmit</h5>
                    <p class="display-5" style="color: #48A111; font-weight: 700;">{{ $submissionCount ?? 0 }}</p>
                    <small class="text-muted">Tugasmu yang sudah dikumpulkan</small>
                </div>
                <a href="{{ route('siswa.assignments.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Materi Tersedia -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">📚 Materi</h5>
                    <p class="display-5" style="color: #25671E; font-weight: 700;">{{ $materialsCount ?? 0 }}</p>
                    <small class="text-muted">Materi pembelajaran</small>
                </div>
                <a href="{{ route('siswa.subjects.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Kehadiran -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">✅ Kehadiran</h5>
                    <p class="display-5" style="color: #F2B50B; font-weight: 700;">{{ $attendanceRate }}%</p>
                    <small class="text-muted">Tingkat kehadiranmu</small>
                </div>
                <a href="{{ route('siswa.attendance.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Nilai Rata-rata -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 position-relative shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">⭐ Nilai Rata-rata</h5>
                    <p class="display-5" style="color: #F7F0F0; font-weight: 700; text-shadow: 1px 1px 2px #25671E;">{{ $averageGrade }}</p>
                    <small class="text-muted">Rata-rata nilaimu</small>
                </div>
                {{-- Link ke tugas juga karena biasanya nilai ada di sana --}}
                <a href="{{ route('siswa.assignments.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Tugas Terbaru Section -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">📌 Tugas Terbaru</h5>
                    <div class="list-group list-group-flush">
                        @forelse($recentAssignments as $assignment)
                            <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3 border-bottom">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $assignment->subject ? $assignment->subject->name : 'N/A' }} - {{ $assignment->title }}</h6>
                                    <small class="text-muted">Deadline: {{ $assignment->due_at }}</small>
                                </div>
                                <span class="badge" style="background-color: #48A111;">Aktif</span>
                            </div>
                        @empty
                            <div class="py-4 text-center text-muted">
                                Tidak ada tugas terbaru.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="col-md-5 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">⚡ Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-primary">
                            📝 Lihat Semua Tugas
                        </a>
                        <a href="{{ route('siswa.subjects.index') }}" class="btn btn-outline-success">
                            📚 Lihat Mata Pelajaran
                        </a>
                        <a href="{{ route('siswa.attendance.index') }}" class="btn btn-outline-warning">
                            ✅ Lihat Kehadiran
                        </a>
                    </div>
                </div>
            </div>

            @if(Auth::user()->student)
                <!-- Parent Access Code Card -->
                <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #eef9ec, #dcfce7); border-left: 5px solid #25671E !important;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2" style="color: #25671E;"><i class="fas fa-user-shield me-2"></i> Akses Orang Tua</h6>
                        <p class="small text-muted mb-3">Berikan kode atau link ini kepada orang tua Anda agar mereka dapat memantau kehadiran, nilai, dan perilaku Anda.</p>
                        <div class="d-flex align-items-center gap-2 mb-2 allow-copy">
                            <code class="bg-white border px-3 py-2 rounded text-dark fw-bold fs-6 flex-grow-1 text-center" style="letter-spacing: 1px;">{{ Auth::user()->student->parent_code }}</code>
                            <button class="btn btn-primary btn-sm px-3 py-2" id="copyParentLink" data-code="{{ Auth::user()->student->parent_code }}">
                                <i class="fas fa-copy"></i> Salin Kode
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Jadwal Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">📅 Jadwal Hari Ini</h5>
                    <div class="small">
                        <div class="mb-3 pb-3 border-bottom">
                            <strong style="color: #25671E;">08:00 - 09:00</strong>
                            <p class="mb-0">Matematika</p>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <strong style="color: #25671E;">09:15 - 10:15</strong>
                            <p class="mb-0">Bahasa Indonesia</p>
                        </div>
                        <div>
                            <strong style="color: #25671E;">10:30 - 11:30</strong>
                            <p class="mb-0">Ilmu Pengetahuan Sosial</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtn = document.getElementById('copyParentLink');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    alert('Kode akses orang tua berhasil disalin: ' + code);
                }).catch(err => {
                    console.error('Gagal menyalin kode: ', err);
                });
            });
        }
    });
</script>
@endpush

