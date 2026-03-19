@extends('layouts.lms')

@section('title', 'Riwayat Absensi')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📜 Riwayat Presensi Mapel</h1>
        <p class="text-muted mb-0">Pilih kelas untuk melihat riwayat kehadiran siswa</p>
    </div>

    @if($attendanceGroups->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-clipboard-list fa-4x text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">Belum ada data riwayat presensi</h5>
            <p class="text-muted small">Silakan lakukan presensi melalui menu Ruang Kelas terlebih dahulu.</p>
        </div>
    @else
        @foreach($attendanceGroups as $major => $subjectGroups)
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="p-2 px-3 rounded-pill text-white fw-bold {{ $major == 'IPA' ? 'bg-primary' : ($major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                        <i class="fas {{ $major == 'IPA' ? 'fa-atom' : ($major == 'IPS' ? 'fa-landmark' : 'fa-folder') }} me-2"></i> JURUSAN {{ strtoupper($major) }}
                    </div>
                    <div class="flex-grow-1 ms-3 border-top opacity-10"></div>
                </div>

                @foreach($subjectGroups as $subjectName => $groups)
                    <div class="ms-lg-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0 fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">
                                <i class="fas fa-bookmark text-muted me-2 small"></i> {{ $subjectName }}
                            </h5>
                            <span class="ms-3 badge rounded-pill bg-light text-muted border px-2 py-1" style="font-size: 0.65rem;">
                                {{ $groups->count() }} Kelas
                            </span>
                        </div>
                        
                        <div class="row">
                            @foreach($groups as $group)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <a href="{{ route('guru.attendances.index', ['class_id' => $group->class_id, 'subject_id' => $group->subject_id]) }}" class="text-decoration-none">
                                        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 4px solid {{ $major == 'IPA' ? '#0d6efd' : ($major == 'IPS' ? '#ffc107' : '#25671E') }}; border-radius: 15px;">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="p-2 rounded-circle me-3" style="background-color: {{ $major == 'IPA' ? '#f0f7ff' : ($major == 'IPS' ? '#fffdf0' : '#f0fdf4') }};">
                                                            <i class="fas fa-history {{ $major == 'IPA' ? 'text-primary' : ($major == 'IPS' ? 'text-warning' : 'text-success') }} small"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold text-dark">{{ $group->schoolClass->name }}</h6>
                                                            <small class="text-muted small">Riwayat Sesi</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-light text-muted border rounded-pill">{{ $group->total_records }} Sesi</span>
                                                </div>
                                                
                                                <div class="mt-3 d-flex align-items-center text-success fw-bold" style="font-size: 0.75rem;">
                                                    Buka Riwayat <i class="fas fa-arrow-right ms-2 mt-1"></i>
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

<style>
    .bg-success-subtle { background-color: rgba(37, 103, 30, 0.1); }
</style>
