@extends('layouts.lms')

@section('title', 'Riwayat Absensi')

@section('content')
    <!-- Header Banner -->
    <div class="content-card mb-4 reveal" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border: none;">
        <div class="content-card-body d-flex justify-content-between align-items-center flex-wrap gap-3" style="padding: 32px 28px;">
            <div>
                <h1 class="mb-2 text-white" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.75rem;">📋 Absensi & Presensi</h1>
                <p class="mb-0" style="color: rgba(255,255,255,0.8);">Pilih kelas untuk melihat riwayat kehadiran siswa</p>
            </div>
            <div>
                <a href="{{ route('guru.meetings.create') }}" class="btn btn-light text-success fw-bold px-4 py-2" style="border-radius: var(--radius-md);">
                    <i class="fas fa-plus me-2"></i> Buat Pertemuan Baru
                </a>
            </div>
        </div>
    </div>

    @if($attendanceGroups->isEmpty())
        <div class="content-card reveal py-5">
            <div class="content-card-body text-center">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-clipboard-list text-success"></i>
                    </div>
                    <div class="empty-state-text">
                        <strong>Belum Ada Data Riwayat Presensi</strong><br>
                        Silakan lakukan presensi melalui menu Ruang Kelas atau buat pertemuan baru terlebih dahulu.
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach($attendanceGroups as $major => $subjectGroups)
            <div class="mb-5 reveal">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="mb-0 fw-extrabold text-uppercase d-flex align-items-center" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary); font-size: 1.1rem; letter-spacing: 0.5px;">
                        <span class="p-2 px-3 rounded-pill text-white fw-bold me-3 {{ $major == 'IPA' ? 'bg-primary' : ($major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}" style="font-size: 0.85rem;">
                            <i class="fas {{ $major == 'IPA' ? 'fa-atom' : ($major == 'IPS' ? 'fa-landmark' : 'fa-folder') }} me-2"></i> JURUSAN {{ strtoupper($major) }}
                        </span>
                    </h4>
                    <div class="flex-grow-1 border-top opacity-10"></div>
                </div>

                @foreach($subjectGroups as $subjectName => $groups)
                    <div class="ms-lg-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0 fw-bold text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">
                                <i class="fas fa-bookmark text-muted me-2 small"></i> {{ $subjectName }}
                            </h5>
                            <span class="ms-3 badge bg-light text-muted border px-2 py-1" style="font-size: 0.75rem; border-radius: var(--radius-sm);">
                                {{ $groups->count() }} Kelas
                            </span>
                        </div>
                        
                        <div class="row">
                            @foreach($groups as $group)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <a href="{{ route('guru.attendances.index', ['class_id' => $group->class_id, 'subject_id' => $group->subject_id]) }}" class="text-decoration-none">
                                        <div class="content-card h-100 mb-0 shadow-sm border-0" style="border-left: 4px solid {{ $major == 'IPA' ? 'var(--primary-light)' : ($major == 'IPS' ? 'var(--accent)' : 'var(--secondary)') }} !important; border-radius: var(--radius-lg); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);">
                                            <div class="content-card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="stat-icon-circle stat-icon-circle--{{ $major == 'IPA' ? 'green' : ($major == 'IPS' ? 'gold' : 'deep') }} me-3">
                                                            <i class="fas fa-history"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">{{ $group->schoolClass->name }}</h6>
                                                            <small class="text-muted" style="font-size: 0.8rem;">Riwayat Sesi</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: rgba(37, 103, 30, 0.06) !important;">
                                                    <span class="status-badge {{ $major == 'IPA' ? 'status-badge--hadir' : 'status-badge--izin' }}">{{ $group->total_records }} Sesi</span>
                                                    <div class="d-flex align-items-center fw-bold" style="font-size: 0.8rem; color: var(--primary);">
                                                        Buka Riwayat <i class="fas fa-arrow-right ms-2"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
@endsection
