@extends('layouts.lms')

@section('title', 'Detail Tugas & Pengumpulan')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ $assignment->meeting_id ? route('guru.meetings.show', $assignment->meeting_id) : route('guru.meetings.index') }}" class="btn btn-outline-secondary btn-sm">
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
                            @if($assignment->isOnline())
                                <span class="badge bg-primary"><i class="fas fa-laptop me-1"></i> Soal Online</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-file-pdf me-1"></i> PDF</span>
                            @endif
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

    @if($assignment->isOnline())
        {{-- Online Assignment: Show questions overview + per-student results --}}
        
        <!-- Questions Overview -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0" style="color: #25671E;"><i class="fas fa-list-ol me-2"></i> Daftar Soal ({{ $assignment->questions->count() }} soal, {{ $assignment->questions->sum('points') }} total poin)</h5>
            </div>
            <div class="card-body p-0">
                @foreach($assignment->questions as $question)
                    <div class="px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-secondary">{{ $question->order }}</span>
                                    @if($question->type === 'pilihan_ganda')
                                        <span class="badge bg-primary" style="font-size: 0.65rem;">Pilihan Ganda</span>
                                    @elseif($question->type === 'isian_singkat')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Isian Singkat</span>
                                    @else
                                        <span class="badge bg-success" style="font-size: 0.65rem;">Essay</span>
                                    @endif
                                    <span class="text-muted small">({{ $question->points }} poin)</span>
                                </div>
                                <p class="mb-1">{{ $question->body }}</p>
                                @if($question->type === 'pilihan_ganda')
                                    <div class="ms-2">
                                        @foreach($question->options as $opt)
                                            <small class="{{ $opt->is_correct ? 'text-success fw-bold' : 'text-muted' }}">
                                                {{ $opt->label }}. {{ $opt->body }}
                                                @if($opt->is_correct) ✓ @endif
                                            </small><br>
                                        @endforeach
                                    </div>
                                @elseif($question->type === 'isian_singkat')
                                    <small class="text-success"><i class="fas fa-check me-1"></i>Jawaban: {{ $question->correct_answer }}</small>
                                @endif
                            </div>

                            {{-- Show answer stats if there are submissions --}}
                            @if($assignment->submissions->count() > 0 && $question->type !== 'essay')
                                @php
                                    $totalAnswers = 0;
                                    $correctAnswers = 0;
                                    foreach ($assignment->submissions as $sub) {
                                        $ans = $sub->questionAnswers->where('question_id', $question->id)->first();
                                        if ($ans) {
                                            $totalAnswers++;
                                            if ($ans->is_correct) $correctAnswers++;
                                        }
                                    }
                                    $pct = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;
                                @endphp
                                <div class="text-center ms-3" style="min-width: 70px;">
                                    <div class="fw-bold" style="font-size: 1.1rem; color: {{ $pct >= 70 ? '#48A111' : ($pct >= 40 ? '#F2B50B' : '#dc3545') }};">{{ $pct }}%</div>
                                    <small class="text-muted" style="font-size: 0.65rem;">Benar</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
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
                            @foreach($assignment->questions as $question)
                                <th class="text-center" title="{{ $question->body }}">
                                    <span class="badge bg-secondary">{{ $question->order }}</span>
                                </th>
                            @endforeach
                            <th class="text-center">Total</th>
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
                                    <small>{{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : '-' }}</small>
                                    @if($assignment->due_at && $submission->submitted_at && \Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->due_at))
                                        <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Terlambat</span>
                                    @endif
                                </td>
                                @foreach($assignment->questions as $question)
                                    @php
                                        $ans = $submission->questionAnswers->where('question_id', $question->id)->first();
                                    @endphp
                                    <td class="text-center">
                                        @if($ans)
                                            @if($ans->is_correct === true)
                                                <span class="text-success fw-bold" title="Benar: {{ $ans->score }}/{{ $question->points }}">✓</span>
                                            @elseif($ans->is_correct === false)
                                                <span class="text-danger fw-bold" title="Salah: {{ $ans->score }}/{{ $question->points }}">✗</span>
                                            @else
                                                <span class="text-warning" title="Belum dinilai">⏳</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="text-center">
                                    @if($submission->score !== null)
                                        <span class="badge" style="background-color: #25671E; font-size: 0.9rem;">{{ $submission->score }}</span>
                                    @else
                                        <span class="text-muted small">Belum</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $submission->id }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="{{ 4 + $assignment->questions->count() }}" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada siswa yang mengumpulkan tugas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @push('modals')
    <!-- Detail Modals -->
    @foreach($assignment->submissions as $submission)
<!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: #25671E; color: white;">
                                            <h5 class="modal-title">📋 Jawaban: {{ $submission->student?->user?->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @foreach($assignment->questions as $question)
                                                @php
                                                    $ans = $submission->questionAnswers->where('question_id', $question->id)->first();
                                                @endphp
                                                <div class="mb-4 p-3 border rounded {{ $ans && $ans->is_correct === true ? 'border-success' : ($ans && $ans->is_correct === false ? 'border-danger' : '') }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <strong>Soal {{ $question->order }}.</strong> {{ $question->body }}
                                                            <span class="text-muted small">({{ $question->points }} poin)</span>
                                                        </div>
                                                        @if($ans)
                                                            @if($ans->is_correct === true)
                                                                <span class="badge bg-success">✓ {{ $ans->score }}/{{ $question->points }}</span>
                                                            @elseif($ans->is_correct === false)
                                                                <span class="badge bg-danger">✗ {{ $ans->score ?? 0 }}/{{ $question->points }}</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">⏳ Belum dinilai</span>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    @if($question->type === 'pilihan_ganda' && $ans)
                                                        <div class="ms-3">
                                                            @foreach($question->options as $opt)
                                                                <div class="small {{ $opt->is_correct ? 'text-success fw-bold' : '' }} {{ $ans->selected_option_id == $opt->id && !$opt->is_correct ? 'text-danger fw-bold' : '' }}">
                                                                    {{ $opt->label }}. {{ $opt->body }}
                                                                    @if($ans->selected_option_id == $opt->id)
                                                                        ← Jawaban siswa
                                                                    @endif
                                                                    @if($opt->is_correct) ✓ @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif($question->type === 'isian_singkat' && $ans)
                                                        <div class="ms-3">
                                                            <div class="small"><strong>Jawaban siswa:</strong> {{ $ans->answer_text }}</div>
                                                            <div class="small text-success"><strong>Jawaban benar:</strong> {{ $question->correct_answer }}</div>
                                                        </div>
                                                    @elseif($question->type === 'essay' && $ans)
                                                        <div class="ms-3 mb-3">
                                                            <div class="small mb-2"><strong>Jawaban siswa:</strong></div>
                                                            <div class="p-2 bg-light rounded small">{{ $ans->answer_text }}</div>
                                                        </div>
                                                        
                                                        <!-- Essay grading form -->
                                                        <form action="{{ route('guru.assignments.grade-question', $ans) }}" method="POST" class="ms-3 p-3 bg-light rounded">
                                                            @csrf
                                                            <div class="row g-2 align-items-end">
                                                                <div class="col-auto">
                                                                    <label class="form-label small fw-bold mb-1">Skor (0-{{ $question->points }})</label>
                                                                    <input type="number" name="score" class="form-control form-control-sm" value="{{ $ans->score ?? '' }}" min="0" max="{{ $question->points }}" required style="width: 80px;">
                                                                </div>
                                                                <div class="col-auto">
                                                                    <label class="form-label small fw-bold mb-1">Benar?</label>
                                                                    <select name="is_correct" class="form-select form-select-sm" required>
                                                                        <option value="1" {{ $ans->is_correct === true ? 'selected' : '' }}>Ya</option>
                                                                        <option value="0" {{ $ans->is_correct === false ? 'selected' : '' }}>Tidak</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i>Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
    @endforeach
    @endpush
    @else
        {{-- PDF Assignment: Original table --}}
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
                                    <small>{{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : '-' }}</small>
                                    @if($assignment->due_at && $submission->submitted_at && \Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->due_at))
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

    @push('modals')
    <!-- Grade Modals -->
    @foreach($assignment->submissions as $submission)
<div class="modal fade" id="gradeModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.assignments.grade-submission', $submission) }}" method="POST">
                                                    @csrf
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
                                                            <textarea name="feedback" class="form-control" rows="3">{{ $submission->feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
    @endforeach
    @endpush
    @endif
@endsection
