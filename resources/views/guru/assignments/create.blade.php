@extends('layouts.lms')

@section('title', 'Buat Tugas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-5">
        <a href="{{ route('guru.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="h3 mb-1">📝 Buat Tugas Baru</h1>
            <p class="text-muted mb-0">Berikan tugas baru untuk siswa Anda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.assignments.store') }}" enctype="multipart/form-data" id="assignmentForm">
                        @csrf

                        <!-- Tipe Tugas -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📋 Tipe Tugas</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="type_pdf" value="pdf" checked onchange="toggleType()">
                                    <label class="form-check-label fw-bold" for="type_pdf">
                                        <i class="fas fa-file-pdf text-danger me-1"></i> Upload PDF
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="type_online" value="online" onchange="toggleType()">
                                    <label class="form-check-label fw-bold" for="type_online">
                                        <i class="fas fa-laptop text-primary me-1"></i> Buat Soal Online
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="mb-4">

                        <!-- Pertemuan, Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-12 mb-4">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🗓️ Pilih Pertemuan (Opsional)</label>
                                <select class="form-select" name="meeting_id" id="meeting_id" style="border-color: #F2B50B; border-width: 2px;">
                                    <option value="">-- Tanpa Pertemuan (Tugas Mandiri) --</option>
                                    @foreach($meetings as $meeting)
                                        <option value="{{ $meeting->id }}" 
                                            data-class="{{ $meeting->class_id }}" 
                                            data-subject="{{ $meeting->subject_id }}"
                                            @selected(old('meeting_id', request('meeting_id')) == $meeting->id)>
                                            Pertemuan {{ $meeting->number }}: {{ $meeting->title }} ({{ $meeting->schoolClass->name }} - {{ $meeting->subject->name }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Jika memilih pertemuan, Kelas dan Mata Pelajaran akan terisi otomatis.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Kelas</label>
                                <select class="form-select" name="class_id" id="class_id" required style="border-color: #25671E;">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📚 Mata Pelajaran</label>
                                <select class="form-select" name="subject_id" id="subject_id" required style="border-color: #25671E;">
                                    @if($subjects->count() > 1)
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                    @endif
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id || $subjects->count() == 1)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const meetingSelect = document.getElementById('meeting_id');
                                const classSelect = document.getElementById('class_id');
                                const subjectSelect = document.getElementById('subject_id');
                                
                                const classCol = classSelect.closest('.col-md-6');
                                const subjectCol = subjectSelect.closest('.col-md-6');

                                function updateFields() {
                                    const selectedOption = meetingSelect.options[meetingSelect.selectedIndex];
                                    if (selectedOption.value) {
                                        // Auto-fill hidden values
                                        const classId = selectedOption.getAttribute('data-class');
                                        const subjectId = selectedOption.getAttribute('data-subject');
                                        if (classId) classSelect.value = classId;
                                        if (subjectId) subjectSelect.value = subjectId;
                                        
                                        // Hide the dropdowns to keep UI clean
                                        classCol.style.display = 'none';
                                        subjectCol.style.display = 'none';
                                    } else {
                                        // Show dropdowns for Tugas Mandiri
                                        classCol.style.display = 'block';
                                        subjectCol.style.display = 'block';
                                    }
                                }

                                meetingSelect.addEventListener('change', updateFields);
                                
                                if (meetingSelect.value) {
                                    updateFields();
                                }
                            });
                        </script>

                        <!-- Judul -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Tugas</label>
                            <input class="form-control" style="border-color: #25671E;" name="title" value="{{ old('title') }}" placeholder="Contoh: Latihan Persamaan Kuadrat" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📄 Deskripsi</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="description" placeholder="Jelaskan detail tugas, instruksi, dan ekspektasi siswa Anda..." rows="4">{{ old('description') }}</textarea>
                            <small class="text-muted">Tuliskan deskripsi yang jelas dan lengkap</small>
                            @error('description')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">⏰ Tanggal & Waktu Deadline</label>
                            <input class="form-control" style="border-color: #25671E;" type="datetime-local" name="due_at" value="{{ old('due_at') }}">
                            <small class="text-muted">Biarkan kosong jika tidak ada deadline</small>
                            @error('due_at')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- PDF Section (shown when type=pdf) -->
                        <div id="pdfSection">
                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📤 File PDF Tugas (Opsional)</label>
                                <input type="file" class="form-control" style="border-color: #25671E;" name="file" accept=".pdf">
                                <small class="text-muted">Pilih file PDF soal/instruksi tugas jika ada (Maksimal 10MB)</small>
                                @error('file')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Online Questions Section (shown when type=online) -->
                        <div id="onlineSection" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <label class="form-label mb-0" style="font-weight: 600; color: #25671E;">📋 Daftar Soal</label>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-plus me-1"></i> Tambah Soal
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="addQuestion('pilihan_ganda')"><i class="fas fa-list-ol me-2 text-primary"></i> Pilihan Ganda</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="addQuestion('isian_singkat')"><i class="fas fa-font me-2 text-warning"></i> Isian Singkat</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="addQuestion('essay')"><i class="fas fa-paragraph me-2 text-success"></i> Essay</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div id="questionsContainer"></div>

                            <div id="emptyQuestions" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                <p>Belum ada soal. Klik <strong>"Tambah Soal"</strong> untuk mulai.</p>
                            </div>

                            @error('questions_json')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror

                            <input type="hidden" name="questions_json" id="questionsJson">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Buat Tugas</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.assignments.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Tips Membuat Tugas</h5>
                    <ul class="small text-muted">
                        <li class="mb-2">Berikan judul yang jelas dan deskriptif</li>
                        <li class="mb-2">Tuliskan instruksi yang detail dan mudah dipahami</li>
                        <li class="mb-2">Tentukan deadline yang realistis</li>
                        <li class="mb-2">Gunakan bahasa yang sederhana dan ringkas</li>
                        <li class="mb-2">Tekankan ekspektasi dan kriteria penilaian</li>
                    </ul>

                    <div id="onlineTips" style="display: none;">
                        <hr>
                        <h6 class="mb-2" style="color: #25671E;">💻 Tips Soal Online</h6>
                        <ul class="small text-muted">
                            <li class="mb-2"><strong>Pilihan Ganda</strong> — dinilai otomatis</li>
                            <li class="mb-2"><strong>Isian Singkat</strong> — dinilai otomatis (exact match)</li>
                            <li class="mb-2"><strong>Essay</strong> — dinilai manual oleh guru</li>
                            <li class="mb-2">Siswa wajib menjawab semua soal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .question-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            background: #fff;
            transition: all 0.2s ease;
            position: relative;
        }
        .question-card:hover {
            border-color: #48A111;
            box-shadow: 0 4px 15px rgba(72, 161, 17, 0.1);
            transform: none;
            cursor: default;
        }
        .question-card .question-number {
            position: absolute;
            top: -12px;
            left: 16px;
            background: #25671E;
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .question-card .question-type-badge {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .option-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .option-label {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        .option-label.correct {
            background: #48A111;
            color: white;
        }
    </style>

    <script>
        let questions = [];
        let questionCounter = 0;

        function toggleType() {
            const isOnline = document.getElementById('type_online').checked;
            document.getElementById('pdfSection').style.display = isOnline ? 'none' : 'block';
            document.getElementById('onlineSection').style.display = isOnline ? 'block' : 'none';
            document.getElementById('onlineTips').style.display = isOnline ? 'block' : 'none';
        }

        function addQuestion(type) {
            questionCounter++;
            const q = {
                id: questionCounter,
                type: type,
                body: '',
                points: 1,
                correct_answer: '',
                options: type === 'pilihan_ganda' ? [
                    { label: 'A', body: '', is_correct: true },
                    { label: 'B', body: '', is_correct: false },
                    { label: 'C', body: '', is_correct: false },
                    { label: 'D', body: '', is_correct: false },
                ] : []
            };
            questions.push(q);
            renderQuestions();
        }

        function removeQuestion(id) {
            if (!confirm('Hapus soal ini?')) return;
            questions = questions.filter(q => q.id !== id);
            renderQuestions();
        }

        function addOption(questionId) {
            const q = questions.find(q => q.id === questionId);
            if (!q || q.options.length >= 5) return;
            const labels = ['A', 'B', 'C', 'D', 'E'];
            q.options.push({ label: labels[q.options.length], body: '', is_correct: false });
            renderQuestions();
        }

        function removeOption(questionId, index) {
            const q = questions.find(q => q.id === questionId);
            if (!q || q.options.length <= 2) return;
            q.options.splice(index, 1);
            // Re-label
            const labels = ['A', 'B', 'C', 'D', 'E'];
            q.options.forEach((opt, i) => opt.label = labels[i]);
            // Ensure at least one correct
            if (!q.options.some(o => o.is_correct) && q.options.length > 0) {
                q.options[0].is_correct = true;
            }
            renderQuestions();
        }

        function moveQuestion(id, direction) {
            const idx = questions.findIndex(q => q.id === id);
            if (idx === -1) return;
            const newIdx = idx + direction;
            if (newIdx < 0 || newIdx >= questions.length) return;
            [questions[idx], questions[newIdx]] = [questions[newIdx], questions[idx]];
            renderQuestions();
        }

        function getTypeBadge(type) {
            switch(type) {
                case 'pilihan_ganda': return '<span class="question-type-badge bg-primary text-white">Pilihan Ganda</span>';
                case 'isian_singkat': return '<span class="question-type-badge bg-warning text-dark">Isian Singkat</span>';
                case 'essay': return '<span class="question-type-badge bg-success text-white">Essay</span>';
            }
        }

        function renderQuestions() {
            const container = document.getElementById('questionsContainer');
            const empty = document.getElementById('emptyQuestions');
            
            if (questions.length === 0) {
                container.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            let html = '';

            questions.forEach((q, index) => {
                html += `<div class="question-card" id="question-${q.id}">`;
                html += `<span class="question-number">Soal ${index + 1}</span>`;
                html += `<div class="d-flex justify-content-between align-items-start mb-3 mt-1">`;
                html += `<div>${getTypeBadge(q.type)}</div>`;
                html += `<div class="d-flex gap-1">`;
                if (index > 0) html += `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="moveQuestion(${q.id}, -1)" title="Pindah ke atas"><i class="fas fa-arrow-up"></i></button>`;
                if (index < questions.length - 1) html += `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="moveQuestion(${q.id}, 1)" title="Pindah ke bawah"><i class="fas fa-arrow-down"></i></button>`;
                html += `<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${q.id})" title="Hapus soal"><i class="fas fa-trash"></i></button>`;
                html += `</div></div>`;

                // Question body
                html += `<div class="mb-3">`;
                html += `<textarea class="form-control" placeholder="Tulis pertanyaan..." rows="2" onchange="updateQuestionField(${q.id}, 'body', this.value)" style="border-color: #25671E;">${q.body}</textarea>`;
                html += `</div>`;

                // Points
                html += `<div class="mb-3 d-flex align-items-center gap-2">`;
                html += `<label class="small fw-bold text-muted mb-0">Poin:</label>`;
                html += `<input type="number" class="form-control form-control-sm" value="${q.points}" min="1" max="100" style="width: 80px; border-color: #25671E;" onchange="updateQuestionField(${q.id}, 'points', parseInt(this.value) || 1)">`;
                html += `</div>`;

                // Options for pilihan_ganda
                if (q.type === 'pilihan_ganda') {
                    html += `<div class="mb-2"><small class="fw-bold text-muted">Pilihan Jawaban (klik lingkaran untuk jawaban benar):</small></div>`;
                    q.options.forEach((opt, optIdx) => {
                        html += `<div class="option-row">`;
                        html += `<div class="option-label ${opt.is_correct ? 'correct' : ''}" onclick="setCorrectOption(${q.id}, ${optIdx})" style="cursor: pointer;" title="Klik untuk jadikan jawaban benar">${opt.label}</div>`;
                        html += `<input type="text" class="form-control form-control-sm" placeholder="Teks pilihan ${opt.label}..." value="${escapeHtml(opt.body)}" onchange="updateOptionField(${q.id}, ${optIdx}, 'body', this.value)">`;
                        if (q.options.length > 2) {
                            html += `<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(${q.id}, ${optIdx})"><i class="fas fa-times"></i></button>`;
                        }
                        html += `</div>`;
                    });
                    if (q.options.length < 5) {
                        html += `<button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addOption(${q.id})"><i class="fas fa-plus me-1"></i>Tambah Pilihan</button>`;
                    }
                }

                // Correct answer for isian_singkat
                if (q.type === 'isian_singkat') {
                    html += `<div class="mb-2"><small class="fw-bold text-muted">Jawaban Benar:</small></div>`;
                    html += `<input type="text" class="form-control form-control-sm" placeholder="Ketik jawaban yang benar..." value="${escapeHtml(q.correct_answer)}" onchange="updateQuestionField(${q.id}, 'correct_answer', this.value)" style="border-color: #48A111;">`;
                    html += `<small class="text-muted">Jawaban siswa akan dicocokkan secara case-insensitive</small>`;
                }

                // Essay info
                if (q.type === 'essay') {
                    html += `<div class="alert alert-light small py-2 px-3 mb-0"><i class="fas fa-info-circle me-1 text-primary"></i> Soal essay akan dinilai manual oleh guru.</div>`;
                }

                html += `</div>`;
            });

            container.innerHTML = html;
        }

        function updateQuestionField(id, field, value) {
            const q = questions.find(q => q.id === id);
            if (q) q[field] = value;
        }

        function updateOptionField(questionId, optIndex, field, value) {
            const q = questions.find(q => q.id === questionId);
            if (q && q.options[optIndex]) q.options[optIndex][field] = value;
        }

        function setCorrectOption(questionId, correctIndex) {
            const q = questions.find(q => q.id === questionId);
            if (!q) return;
            q.options.forEach((opt, i) => opt.is_correct = (i === correctIndex));
            renderQuestions();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        // Before form submit, serialize questions to JSON
        document.getElementById('assignmentForm').addEventListener('submit', function(e) {
            const isOnline = document.getElementById('type_online').checked;
            if (isOnline) {
                if (questions.length === 0) {
                    e.preventDefault();
                    alert('Minimal 1 soal harus ditambahkan untuk tugas online.');
                    return;
                }
                // Validate all questions have body text
                for (let i = 0; i < questions.length; i++) {
                    if (!questions[i].body.trim()) {
                        e.preventDefault();
                        alert(`Soal ${i + 1} belum diisi teks pertanyaannya.`);
                        return;
                    }
                    if (questions[i].type === 'pilihan_ganda') {
                        for (let j = 0; j < questions[i].options.length; j++) {
                            if (!questions[i].options[j].body.trim()) {
                                e.preventDefault();
                                alert(`Soal ${i + 1}, pilihan ${questions[i].options[j].label} belum diisi.`);
                                return;
                            }
                        }
                    }
                    if (questions[i].type === 'isian_singkat' && !questions[i].correct_answer.trim()) {
                        e.preventDefault();
                        alert(`Soal ${i + 1} (Isian Singkat) belum diisi jawaban benarnya.`);
                        return;
                    }
                }
                document.getElementById('questionsJson').value = JSON.stringify(questions);
            }
        });
    </script>
@endsection
