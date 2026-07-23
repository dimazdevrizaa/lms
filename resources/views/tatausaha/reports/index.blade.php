@extends('layouts.lms')

@section('title', 'Laporan Akademik')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 reveal">
        <div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 4px;">
                📋 Laporan Akademik
            </h1>
            <p class="text-muted mb-0 small">Ringkasan dan cetak laporan data sekolah</p>
        </div>
        <a class="btn btn-outline-primary-theme" href="{{ route('tatausaha.reports.print') }}" target="_blank">
            <i class="fas fa-print me-1"></i> Cetak Laporan
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid reveal reveal-delay-1 mb-4">
        {{-- Total Siswa --}}
        <div class="stat-card stat-card--attendance">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--green">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="stat-label">Ringkasan Siswa</div>
            <div class="stat-value stat-value--green">{{ $students->count() }}</div>
            <div class="stat-sub">Siswa terdaftar</div>
        </div>

        {{-- Total Guru --}}
        <div class="stat-card stat-card--grades">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon-circle stat-icon-circle--gold">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
            <div class="stat-label">Ringkasan Guru</div>
            <div class="stat-value stat-value--primary">{{ $teachers->count() }}</div>
            <div class="stat-sub">Guru aktif</div>
        </div>
    </div>

    {{-- Details Sections --}}
    <div class="row">
        {{-- Data Siswa Card --}}
        <div class="col-lg-6 mb-4">
            <div class="content-card reveal reveal-delay-2 h-100">
                <div class="content-card-header">
                    <div class="content-card-header-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="content-card-title">Data Siswa</h2>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="position: sticky; top: 0; background: var(--bg-card); z-index: 2; box-shadow: 0 1px 0 rgba(0,0,0,0.05);">
                                <tr>
                                    <th class="ps-4">NISN</th>
                                    <th>Nama</th>
                                    <th class="pe-4">Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td class="ps-4"><code style="color: var(--primary);">{{ $student->nisn }}</code></td>
                                        <td><strong>{{ $student->user?->name ?? '-' }}</strong></td>
                                        <td class="pe-4">
                                            <span class="status-badge status-badge--hadir">
                                                {{ $student->schoolClass?->name ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">Tidak ada data siswa</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Guru Card --}}
        <div class="col-lg-6 mb-4">
            <div class="content-card reveal reveal-delay-2 h-100">
                <div class="content-card-header">
                    <div class="content-card-header-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h2 class="content-card-title">Data Guru</h2>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="position: sticky; top: 0; background: var(--bg-card); z-index: 2; box-shadow: 0 1px 0 rgba(0,0,0,0.05);">
                                <tr>
                                    <th class="ps-4">Nama</th>
                                    <th>Email</th>
                                    <th class="pe-4">NIP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $teacher->user?->name ?? '-' }}</strong></td>
                                        <td><small class="text-muted">{{ $teacher->user?->email ?? '-' }}</small></td>
                                        <td class="pe-4"><code style="color: var(--primary);">{{ $teacher->nip ?? '-' }}</code></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">Tidak ada data guru</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
