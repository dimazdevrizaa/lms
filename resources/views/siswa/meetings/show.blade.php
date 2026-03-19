@extends('layouts.lms')

@section('title', 'Detail Pertemuan')

@section('content')
    <div class="mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('siswa.subjects.index') }}" style="color: #48A111;">Mata Pelajaran</a></li>
                <li class="breadcrumb-item"><a href="{{ route('siswa.subjects.show', $meeting->subject_id) }}" style="color: #48A111;">{{ $meeting->subject->name }}</a></li>
                <li class="breadcrumb-item active">Pertemuan {{ $meeting->number }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="badge mb-2" style="background-color: #48A111; padding: 8px 15px;">Pertemuan ke-{{ $meeting->number }}</span>
                <h1 class="h3 mb-1">{{ $meeting->title }}</h1>
                <div class="text-muted small">
                    <span class="me-3"><i class="fas fa-chalkboard-user me-1"></i> {{ $meeting->teacher->user->name }}</span>
                    <span><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</span>
                </div>
            </div>
            <a href="{{ route('siswa.subjects.show', $meeting->subject_id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if($meeting->description)
        <div class="card mb-5 border-0 shadow-sm" style="border-radius: 15px; border-left: 5px solid #F2B50B;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2 text-muted">Deskripsi / Instruksi:</h6>
                <p class="mb-0 text-dark">{{ $meeting->description }}</p>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Materi Section -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold" style="color: #25671E;"><i class="fas fa-book me-2"></i> Materi Pembelajaran</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @forelse($meeting->materials as $m)
                        <div class="d-flex align-items-center p-3 mb-3 bg-light rounded border-start border-4 border-success item-card" 
                             style="cursor: pointer; transition: 0.2s;"
                             onclick="window.open('{{ asset('storage/' . $m->file_path) }}', '_blank')">
                            <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $m->title }}</h6>
                                <p class="text-muted small mb-0">{{ Str::limit($m->content, 50) }}</p>
                            </div>
                            <span class="btn btn-sm btn-success rounded-pill px-3">Buka</span>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                            <p class="text-muted">Tidak ada materi untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tugas Section -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold" style="color: #F2B50B;"><i class="fas fa-tasks me-2"></i> Tugas Siswa</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @forelse($meeting->assignments as $a)
                        <div class="p-3 mb-3 bg-light rounded border-start border-4 border-warning">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-edit text-warning fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $a->title }}</h6>
                                        <small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i> Deadline: {{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($a->file_path)
                                <a href="{{ asset('storage/' . $a->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger w-100 mb-3">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat PDF Soal
                                </a>
                            @endif

                            @php
                                $submission = $a->submissions()->where('student_id', $student->id)->first();
                            @endphp

                            @if($submission)
                                <div class="alert alert-success py-2 px-3 small border-0 mb-0">
                                    <i class="fas fa-check-circle me-1"></i> Anda sudah mengumpulkan tugas ini.
                                </div>
                            @elseif($a->due_at && \Carbon\Carbon::parse($a->due_at)->isPast())
                                <div class="alert alert-danger py-2 px-3 small border-0 mb-0">
                                    <i class="fas fa-times-circle me-1"></i> Deadline sudah lewat.
                                </div>
                            @else
                                <form method="POST" action="{{ route('siswa.assignments.submit', $a) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea class="form-control form-control-sm" name="answer_text" placeholder="Catatan/Jawaban singkat (opsional)..." rows="2"></textarea>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="file" class="form-control" name="file" accept=".pdf" required>
                                        <label class="input-group-text">PDF</label>
                                    </div>
                                    <button class="btn btn-sm w-100" style="background-color: #48A111; color: white;" type="submit">Kirim Jawaban</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                            <p class="text-muted">Tidak ada tugas untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .item-card:hover {
            transform: translateY(-3px);
            background-color: #e9ecef !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
@endsection
