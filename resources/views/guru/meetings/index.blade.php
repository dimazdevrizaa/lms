@extends('layouts.lms')

@section('title', 'Ruang Kelas (Materi & Tugas)')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">🏫 Ruang Kelas Saya</h1>
            <p class="text-muted mb-0">Kelola materi, tugas, dan pertemuan berdasarkan Kelas & Mata Pelajaran</p>
        </div>
        <a class="btn btn-sm" style="background-color: #48A111; color: white; border: none; box-shadow: 0 2px 8px rgba(72,161,17, 0.2);" href="{{ route('guru.meetings.create') }}">
            <i class="fas fa-plus me-2"></i> Buat Ruang Kelas Baru
        </a>
    </div>

    @if($meetingGroups->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-chalkboard-teacher fa-4x mb-4 text-light"></i>
                <h5 class="mb-3">📭 Belum ada data pembelajaran</h5>
                <p class="text-muted mb-4">Anda belum memiliki pertemuan atau materi yang dibagikan.</p>
                <a href="{{ route('guru.meetings.create') }}" class="btn btn-primary" style="background-color: #48A111; border: none;">Mulai Sesi Pembelajaran</a>
            </div>
        </div>
    @else
        @foreach($meetingGroups as $major => $subjectGroups)
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
                                    <div class="card h-100 class-group-card border-0 shadow-sm" 
                                         style="cursor: pointer; border-radius: 18px; transition: all 0.3s ease; border-left: 4px solid {{ $major == 'IPA' ? '#0d6efd' : ($major == 'IPS' ? '#ffc107' : '#25671E') }};"
                                         onclick="window.location='{{ route('guru.meetings.index', ['class_id' => $group->class_id, 'subject_id' => $group->subject_id]) }}'">
                                        
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="p-2 rounded-circle me-3" style="background-color: {{ $major == 'IPA' ? '#f0f7ff' : ($major == 'IPS' ? '#fffdf0' : '#f0fdf4') }};">
                                                        <i class="fas fa-users-viewfinder {{ $major == 'IPA' ? 'text-primary' : ($major == 'IPS' ? 'text-warning' : 'text-success') }} small"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-bold">{{ $group->schoolClass?->name ?? 'Tanpa Kelas' }}</h6>
                                                </div>
                                                <div class="badge bg-light text-muted small border">{{ $group->total_meetings }} Sesi</div>
                                            </div>
                                            
                                            <div class="card-footer bg-transparent border-0 p-0 mt-3 d-flex gap-2">
                                                <a href="{{ route('guru.meetings.index', ['class_id' => $group->class_id, 'subject_id' => $group->subject_id]) }}" class="btn btn-sm flex-grow-1 rounded-pill py-2" style="font-size: 0.75rem; background-color: {{ $major == 'IPA' ? '#f0f7ff' : ($major == 'IPS' ? '#fffdf0' : '#f0fdf4') }}; color: {{ $major == 'IPA' ? '#0d6efd' : ($major == 'IPS' ? '#d39e00' : '#25671E') }}; border: 1px solid {{ $major == 'IPA' ? '#cfe2ff' : ($major == 'IPS' ? '#ffecb5' : '#dcfce7') }};">
                                                    Buka <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                                <a href="{{ route('guru.meetings.create', ['class_id' => $group->class_id, 'subject_id' => $group->subject_id]) }}" class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Tambah Sesi">
                                                    <i class="fas fa-plus small"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif

        <style>
            .class-group-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(37, 103, 30, 0.15) !important;
            }
            .class-group-card:hover .btn {
                background-color: #48A111 !important;
                color: white !important;
            }
        </style>
@endsection
