@extends('layouts.lms')

@section('title', 'Detail Pertemuan')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('guru.meetings.index') }}" style="color: #48A111;">Pertemuan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0">🗓️ Pertemuan ke-{{ $meeting->number }}: {{ $meeting->title }}</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('guru.meetings.edit', $meeting) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('guru.meetings.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted">
            <span><i class="fas fa-door-open me-1"></i> {{ $meeting->schoolClass->name }}</span>
            <span><i class="fas fa-book me-1"></i> {{ $meeting->subject->name }}</span>
            <span><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</span>
        </div>
    </div>

    @if($meeting->description)
        <div class="card mb-4" style="border-left: 4px solid #F2B50B;">
            <div class="card-body">
                <h6 class="card-title text-muted mb-2">Deskripsi/Tujuan:</h6>
                <p class="mb-0">{{ $meeting->description }}</p>
            </div>
        </div>
    @endif

    <!-- Absensi Section -->
    <div class="card mb-5 overflow-hidden shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-success"><i class="fas fa-user-check me-2"></i> Presensi Siswa</h5>
            @if($meeting->attendance)
                <a href="{{ route('guru.attendances.show', $meeting->attendance) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Detail Presensi
                </a>
            @else
                <a href="{{ route('guru.attendances.create', ['meeting_id' => $meeting->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Isi Presensi
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($meeting->attendance)
                <div class="row text-center mb-0">
                    <div class="col-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h4 class="mb-1 text-primary">{{ $meeting->attendance->details->where('status', 'hadir')->count() }}</h4>
                            <span class="text-muted small fw-bold">HADIR</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h4 class="mb-1 text-warning">{{ $meeting->attendance->details->where('status', 'izin')->count() }}</h4>
                            <span class="text-muted small fw-bold">IZIN</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h4 class="mb-1 text-info">{{ $meeting->attendance->details->where('status', 'sakit')->count() }}</h4>
                            <span class="text-muted small fw-bold">SAKIT</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h4 class="mb-1 text-danger">{{ $meeting->attendance->details->where('status', 'alpa')->count() }}</h4>
                            <span class="text-muted small fw-bold">ALPA</span>
                        </div>
                    </div>
                </div>

                @php
                    $absentees = $meeting->attendance->details->whereIn('status', ['izin', 'sakit', 'alpa']);
                @endphp
                
                @if($absentees->isNotEmpty())
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3"><i class="fas fa-exclamation-circle me-1"></i> Daftar Siswa Tidak Hadir:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absentees as $absentee)
                                        <tr>
                                            <td>{{ $absentee->student->user->name }}</td>
                                            <td>
                                                <span class="badge @if($absentee->status == 'alpa') bg-danger @elseif($absentee->status == 'sakit') bg-info @else bg-warning @endif">
                                                    {{ strtoupper($absentee->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mt-3 text-center text-success small">
                        <i class="fas fa-check-circle me-1"></i> Semua siswa hadir.
                    </div>
                @endif
            @else
                <div class="text-center py-4 text-muted">
                    <p class="mb-0">Daftar presensi belum diisi untuk pertemuan ini.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Materi Section -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0" style="color: #25671E;"><i class="fas fa-book me-2"></i> Materi Pembelajaran</h5>
                    <a href="{{ route('guru.materials.create', ['meeting_id' => $meeting->id]) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    @forelse($meeting->materials as $material)
                        <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $material->title }}</h6>
                                @if($material->file_path)
                                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="small text-danger" style="text-decoration: none;">
                                        <i class="fas fa-file-pdf me-1"></i> Lihat PDF
                                    </a>
                                @endif
                            </div>
                            <a href="{{ route('guru.materials.edit', $material) }}" class="btn btn-sm btn-light" title="Edit Materi">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada materi untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tugas Section -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0" style="color: #25671E;"><i class="fas fa-tasks me-2"></i> Tugas Siswa</h5>
                    <a href="{{ route('guru.assignments.create', ['meeting_id' => $meeting->id]) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    @forelse($meeting->assignments as $assignment)
                        <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $assignment->title }}</h6>
                                <small class="text-muted d-block mb-1">Deadline: {{ \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') }}</small>
                            </div>
                            <a href="{{ route('guru.assignments.edit', $assignment) }}" class="btn btn-sm btn-light" title="Edit Tugas">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <p>Belum ada tugas untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
