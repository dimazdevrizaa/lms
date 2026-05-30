@extends('layouts.lms')

@section('title', $assignment->title)

@section('content')
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="h3 mb-0">{{ $submission ? '📊 Hasil Tugas' : '📝 Kerjakan Tugas' }}</h1>
        </div>

        <div class="card bg-light border-0">
            <div class="card-body p-4">
                <h4 style="color: #25671E; font-weight: 700;">{{ $assignment->title }}</h4>
                @if($assignment->description)
                    <p class="text-muted mb-2">{{ $assignment->description }}</p>
                @endif
                <div class="d-flex gap-3 flex-wrap">
                    <span class="badge bg-primary"><i class="fas fa-laptop me-1"></i> Soal Online</span>
                    <span class="text-muted small"><i class="fas fa-list-ol me-1"></i> {{ $assignment->questions->count() }} soal</span>
                    <span class="text-muted small"><i class="fas fa-star me-1"></i> {{ $assignment->questions->sum('points') }} total poin</span>
                    @if($assignment->due_at)
                        <span class="text-muted small"><i class="fas fa-clock me-1"></i> Deadline: {{ \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($submission)
        {{-- Already submitted: show results --}}
        <div class="card mb-4" style="border-top: 4px solid #25671E;">
            <div class="card-body text-center py-4">
                <h5 style="color: #25671E;">✅ Tugas sudah dikumpulkan</h5>
                <p class="text-muted mb-2">Dikumpulkan pada {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, H:i') }}</p>
                @if($submission->score !== null)
                    <div class="d-inline-block bg-light rounded-3 p-3 mt-2">
                        <h2 class="mb-0" style="color: #25671E; font-weight: 800;">{{ $submission->score }}</h2>
                        <small class="text-muted text-uppercase fw-bold">Nilai</small>
                    </div>
                @else
                    <span class="badge bg-warning text-dark fs-6 mt-2"><i class="fas fa-hourglass-half me-1"></i> Menunggu penilaian guru</span>
                @endif
            </div>
        </div>

        {{-- Show answers with results --}}
        @foreach($assignment->questions as $question)
            @php
                $ans = $answers[$question->id] ?? null;
            @endphp
            <div class="card mb-3 {{ $ans && $ans->is_correct === true ? 'border-success' : ($ans && $ans->is_correct === false ? 'border-danger' : '') }}" style="border-width: 2px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-secondary me-1">{{ $question->order }}</span>
                            @if($question->type === 'pilihan_ganda')
                                <span class="badge bg-primary" style="font-size: 0.65rem;">Pilihan Ganda</span>
                            @elseif($question->type === 'isian_singkat')
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Isian Singkat</span>
                            @else
                                <span class="badge bg-success" style="font-size: 0.65rem;">Essay</span>
                            @endif
                            <span class="text-muted small ms-1">({{ $question->points }} poin)</span>
                        </div>
                        @if($ans)
                            @if($ans->is_correct === true)
                                <span class="badge bg-success">✓ Benar ({{ $ans->score }}/{{ $question->points }})</span>
                            @elseif($ans->is_correct === false)
                                <span class="badge bg-danger">✗ Salah ({{ $ans->score ?? 0 }}/{{ $question->points }})</span>
                            @else
                                <span class="badge bg-warning text-dark">⏳ Belum dinilai</span>
                            @endif
                        @endif
                    </div>
                    
                    <p class="mb-3 fw-bold">{{ $question->body }}</p>

                    @if($question->type === 'pilihan_ganda')
                        @foreach($question->options as $opt)
                            <div class="d-flex align-items-center gap-2 p-2 rounded mb-1 
                                {{ $opt->is_correct ? 'bg-success bg-opacity-10' : '' }}
                                {{ $ans && $ans->selected_option_id == $opt->id && !$opt->is_correct ? 'bg-danger bg-opacity-10' : '' }}">
                                <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
                                      style="width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;
                                      {{ $opt->is_correct ? 'background: #48A111; color: white;' : ($ans && $ans->selected_option_id == $opt->id ? 'background: #dc3545; color: white;' : 'background: #e2e8f0;') }}">
                                    {{ $opt->label }}
                                </span>
                                <span class="{{ $opt->is_correct ? 'text-success fw-bold' : '' }} {{ $ans && $ans->selected_option_id == $opt->id && !$opt->is_correct ? 'text-danger' : '' }}">
                                    {{ $opt->body }}
                                </span>
                                @if($ans && $ans->selected_option_id == $opt->id)
                                    <small class="text-muted ms-auto">(Jawaban Anda)</small>
                                @endif
                            </div>
                        @endforeach
                    @elseif($question->type === 'isian_singkat' && $ans)
                        <div class="ms-2">
                            <div class="small mb-1"><strong>Jawaban Anda:</strong> <span class="{{ $ans->is_correct ? 'text-success' : 'text-danger' }}">{{ $ans->answer_text }}</span></div>
                            @if(!$ans->is_correct)
                                <div class="small text-success"><strong>Jawaban benar:</strong> {{ $question->correct_answer }}</div>
                            @endif
                        </div>
                    @elseif($question->type === 'essay' && $ans)
                        <div class="ms-2">
                            <div class="p-3 bg-light rounded small">{{ $ans->answer_text }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        {{-- Not yet submitted: show answer form --}}
        <form method="POST" action="{{ route('siswa.assignments.submit', $assignment) }}" id="quizForm">
            @csrf

            @foreach($assignment->questions as $question)
                <div class="card mb-3 question-card-student" id="question-card-{{ $question->id }}" style="border-left: 4px solid #25671E;">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2 mb-3">
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

                        <p class="fw-bold mb-3">{{ $question->body }}</p>

                        @if($question->type === 'pilihan_ganda')
                            @foreach($question->options as $opt)
                                <div class="form-check mb-2 p-2 rounded border option-select" style="cursor: pointer;">
                                    <input class="form-check-input" type="radio" 
                                           name="answers[{{ $question->id }}][selected_option_id]" 
                                           value="{{ $opt->id }}" 
                                           id="opt_{{ $opt->id }}"
                                           {{ old("answers.{$question->id}.selected_option_id") == $opt->id ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="opt_{{ $opt->id }}" style="cursor: pointer;">
                                        <strong class="me-2">{{ $opt->label }}.</strong> {{ $opt->body }}
                                    </label>
                                </div>
                            @endforeach
                        @elseif($question->type === 'isian_singkat')
                            <input type="text" class="form-control" 
                                   name="answers[{{ $question->id }}][answer_text]"
                                   placeholder="Ketik jawaban Anda..." 
                                   value="{{ old("answers.{$question->id}.answer_text") }}"
                                   style="border-color: #25671E;">
                        @elseif($question->type === 'essay')
                            <textarea class="form-control" 
                                      name="answers[{{ $question->id }}][answer_text]"
                                      placeholder="Tulis jawaban essay Anda..." 
                                      rows="4"
                                      style="border-color: #25671E;">{{ old("answers.{$question->id}.answer_text") }}</textarea>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="card" style="border-top: 4px solid #48A111;">
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-3"><i class="fas fa-info-circle me-1"></i> Pastikan semua soal sudah dijawab sebelum mengirim.</p>
                    <button type="submit" class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" 
                            onclick="return confirm('Kirim jawaban? Jawaban tidak dapat diubah setelah dikirim.')">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Jawaban
                    </button>
                </div>
            </div>
        </form>

        <style>
            .option-select:has(input:checked) {
                background-color: rgba(37, 103, 30, 0.08);
                border-color: #25671E !important;
            }
            .option-select:hover {
                background-color: rgba(37, 103, 30, 0.04);
            }
        </style>
    @endif
@endsection
