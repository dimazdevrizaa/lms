@extends('layouts.lms')

@section('title', 'Detail Tugas & Pengumpulan')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('guru.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="h3 mb-0">📋 Detail Tugas & Pengumpulan</h1>
        </div>
        
        <div class="card bg-light border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 style="color: #25671E; font-weight: 700;">{{ $assignment->title }}</h4>
                        <p class="text-muted mb-2">{{ $assignment->description }}</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <span class="badge" style="background-color: #48A111;">{{ $assignment->schoolClass?->name }}</span>
                            <span class="text-muted small"><i class="fas fa-clock me-1"></i> Deadline: {{ $assignment->due_at ? \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') : 'Tidak ada' }}</span>
                            @if($assignment->file_path)
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="text-danger small fw-bold text-decoration-none">
                                    <i class="fas fa-file-pdf me-1"></i> File Instruksi PDF
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="bg-white p-3 rounded-3 shadow-sm d-inline-block text-center">
                            <h3 class="mb-0" style="color: #25671E;">{{ $assignment->submissions->count() }}</h3>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Pengumpulan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0" style="color: #25671E;"><i class="fas fa-users me-2"></i> Daftar Pengumpulan Siswa</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background-color: #F7F0F0;">
                    <tr>
                        <th style="border-left: 4px solid #25671E; color: #25671E;">Nama Siswa</th>
                        <th>Waktu Kumpul</th>
                        <th>Jawaban Teks</th>
                        <th>File PDF</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignment->submissions as $submission)
                        <tr>
                            <td>
                                <div><strong>{{ $submission->student?->user?->name ?? 'Siswa' }}</strong></div>
                                <small class="text-muted">NIS: {{ $submission->student?->nis ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $submission->submitted_at->format('d/m/Y H:i') }}</small>
                                @if($assignment->due_at && $submission->submitted_at->gt($assignment->due_at))
                                    <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                <span title="{{ $submission->answer_text }}">
                                    {{ Str::limit($submission->answer_text, 50) ?: '-' }}
                                </span>
                            </td>
                            <td>
                                @if($submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf"></i> Lihat PDF
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->score !== null)
                                    <span class="badge" style="background-color: #25671E; font-size: 0.9rem;">{{ $submission->score }}</span>
                                @else
                                    <span class="text-muted small">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $submission->id }}">
                                    <i class="fas fa-check-circle me-1"></i> Nilai
                                </button>
                                
                                <!-- Grading Modal (Simple placeholder for now) -->
                                <div class="modal fade" id="gradeModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="#" method="POST"> <!-- Route for grading can be added later -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Beri Nilai: {{ $submission->student?->user?->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label">Skor (0-100)</label>
                                                        <input type="number" name="score" class="form-control" value="{{ $submission->score }}" min="0" max="100" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan Guru</label>
                                                        <textarea name="teacher_notes" class="form-control" rows="3">{{ $submission->teacher_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary" disabled title="Fitur penilaian akan segera hadir">Simpan Nilai</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada siswa yang mengumpulkan tugas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
