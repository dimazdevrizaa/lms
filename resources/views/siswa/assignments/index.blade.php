@extends('layouts.lms')

@section('title', 'Tugas')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📋 Tugas Saya</h1>
        <p class="text-muted mb-0">Kumpulkan dan pantau kemajuan tugas Anda</p>
    </div>

    @if(!$student)
        <div class="alert alert-warning border-top-4" style="border-top-color: #F2B50B;">
            <strong>⚠️ Perhatian</strong>
            <p class="mb-0 mt-2">Profil siswa belum terdaftar. Silakan minta Tata Usaha membuat data siswa untuk akun ini.</p>
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <h5 class="mb-3">📭 Belum ada tugas</h5>
                <p class="text-muted mb-0">Guru akan memberikan tugas untuk Anda. Pantau halaman ini untuk tugas terbaru.</p>
            </div>
        </div>
    @else
        <!-- Assignments Cards -->
        <div class="row">
            @forelse($assignments as $assignment)
                @php
                    $isSubmitted = in_array($assignment->id, $submittedIds ?? []);
                @endphp
                <div class="col-md-6 mb-4">
                    <div class="card h-100" style="border-top: 4px solid {{ $assignment->isOnline() ? '#0d6efd' : '#25671E' }};">
                        <div class="card-body">
                            <!-- Title & Subject -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title" style="color: #25671E;">{{ $assignment->title }}</h5>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($assignment->isOnline())
                                            <span class="badge bg-primary"><i class="fas fa-laptop me-1"></i>Online</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-file-pdf me-1"></i>PDF</span>
                                        @endif
                                        @if($isSubmitted)
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Dikumpulkan</span>
                                        @endif
                                    </div>
                                </div>
                                @if($assignment->due_at && \Carbon\Carbon::parse($assignment->due_at)->isFuture())
                                    <span class="badge" style="background-color: #48A111;">✓ Aktif</span>
                                @else
                                    <span class="badge" style="background-color: #F2B50B;">⊘ Ditutup</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-muted small mb-3">{{ Str::limit($assignment->description, 100) }}</p>

                            <!-- Deadline -->
                            @if($assignment->due_at)
                                <div class="alert alert-light border-left-4" style="border-left-color: #25671E; padding: 10px; margin-bottom: 15px;">
                                    <small>
                                        <strong>⏰ Deadline:</strong>
                                        {{ \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') }}
                                    </small>
                                </div>
                            @endif

                            @if($student)
                                @if($assignment->isOnline())
                                    {{-- Online Assignment --}}
                                    @if($isSubmitted)
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn w-100" style="background-color: #25671E; color: white; border: none;">
                                            <i class="fas fa-chart-bar me-1"></i> Lihat Hasil
                                        </a>
                                    @else
                                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn w-100" style="background-color: #0d6efd; color: white; border: none;">
                                            <i class="fas fa-pen me-1"></i> Kerjakan Online
                                        </a>
                                    @endif
                                @else
                                    {{-- PDF Assignment --}}
                                    @if($assignment->file_path)
                                        <div class="mb-3">
                                            <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-file-pdf me-1"></i> Download / Lihat PDF Soal
                                            </a>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('siswa.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Jawaban Teks (Opsional)</label>
                                            <textarea class="form-control" name="answer_text" placeholder="Ketik jawaban Anda di sini..." rows="2" style="border-color: #25671E;"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Upload PDF (Opsional)</label>
                                            <input type="file" class="form-control form-control-sm" name="file" accept=".pdf" style="border-color: #25671E;">
                                        </div>
                                        <button class="btn w-100" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Kirim Tugas</button>
                                    </form>
                                @endif
                            @else
                                <div class="alert alert-info small mb-0">
                                    Data siswa belum terdaftar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Belum ada tugas.</div>
                </div>
            @endforelse
        </div>
    @endif
@endsection
