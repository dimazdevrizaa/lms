@extends('layouts.lms')

@section('title', 'Input Nilai - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.grades', $class) }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">📋 Input Nilai Siswa - {{ $class->name }}</h1>
        <p class="text-muted mb-0">Masukkan nilai siswa untuk berbagai penilaian</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.classroom.grades.store', $class) }}" id="gradesForm">
                        @csrf

                        <div id="gradesContainer">
                            <div class="grade-entry mb-4 p-3 border rounded" style="background-color: #F7F0F0;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" style="font-weight: 600; color: #25671E;">👤 Siswa</label>
                                        <select class="form-select" style="border-color: #25671E;" name="grades[0][student_id]" required>
                                            <option value="">-- Pilih Siswa --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->user->name }} (NIS: {{ $student->nis }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                                        <select class="form-select" style="border-color: #25671E;" name="grades[0][subject_id]" required>
                                            <option value="">-- Pilih Mata Pelajaran --</option>
                                            @php
                                                $teacher = App\Models\Teacher::where('user_id', auth()->id())->first();
                                                $mySubjects = $teacher ? $teacher->subjects()->orderBy('name')->get() : collect();
                                                
                                                // Jika guru punya mata pelajaran diampu, gunakan itu, jika tidak tampilkan semua
                                                $subjectsToDisplay = $mySubjects->isNotEmpty() ? $mySubjects : \App\Models\Subject::orderBy('name')->get();
                                            @endphp
                                            @foreach($subjectsToDisplay as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" style="font-weight: 600; color: #25671E;">🎯 Jenis Penilaian</label>
                                        <input class="form-control" style="border-color: #25671E;" type="text" name="grades[0][assessment_type]" placeholder="Contoh: UTS, UAS, Tugas" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" style="font-weight: 600; color: #25671E;">📊 Nilai (0-100)</label>
                                        <input class="form-control" style="border-color: #25671E;" type="number" name="grades[0][score]" min="0" max="100" step="0.1" placeholder="Contoh: 85" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                                        <input class="form-control" style="border-color: #25671E;" type="date" name="grades[0][assessment_date]" value="{{ now()->toDateString() }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Entry -->
                        <div class="mb-4">
                            <button type="button" class="btn btn-outline-secondary" id="addGradeBtn">
                                ➕ Tambah Penilaian Lain
                            </button>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">
                                ✓ Simpan Semua Nilai
                            </button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.classroom.grades', $class) }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data subjects untuk digunakan di JavaScript
        @php
            $teacher = App\Models\Teacher::where('user_id', auth()->id())->first();
            $mySubjects = $teacher ? $teacher->subjects()->orderBy('name')->get() : collect();
            $subjectsData = $mySubjects->isNotEmpty() ? $mySubjects : \App\Models\Subject::orderBy('name')->get();
        @endphp
        const subjectsData = @json($subjectsData);
        const studentsData = @json($students->map(fn($s) => ['id' => $s->id, 'name' => $s->user->name, 'nis' => $s->nis]));

        document.getElementById('addGradeBtn').addEventListener('click', function() {
            const container = document.getElementById('gradesContainer');
            const entries = container.querySelectorAll('.grade-entry').length;
            
            const newEntry = document.createElement('div');
            newEntry.className = 'grade-entry mb-4 p-3 border rounded';
            newEntry.style.backgroundColor = '#F7F0F0';
            
            // Build students options
            let studentsOptions = '<option value="">-- Pilih Siswa --</option>';
            studentsData.forEach(student => {
                studentsOptions += `<option value="${student.id}">${student.name} (NIS: ${student.nis})</option>`;
            });

            // Build subjects options
            let subjectsOptions = '<option value="">-- Pilih Mata Pelajaran --</option>';
            subjectsData.forEach(subject => {
                subjectsOptions += `<option value="${subject.id}">${subject.name}</option>`;
            });

            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-weight: 600; color: #25671E;">👤 Siswa</label>
                        <select class="form-select" style="border-color: #25671E;" name="grades[${entries}][student_id]" required>
                            ${studentsOptions}
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                        <select class="form-select" style="border-color: #25671E;" name="grades[${entries}][subject_id]" required>
                            ${subjectsOptions}
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-weight: 600; color: #25671E;">🎯 Jenis Penilaian</label>
                        <input class="form-control" style="border-color: #25671E;" type="text" name="grades[${entries}][assessment_type]" placeholder="Contoh: UTS, UAS, Tugas" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-weight: 600; color: #25671E;">📊 Nilai (0-100)</label>
                        <input class="form-control" style="border-color: #25671E;" type="number" name="grades[${entries}][score]" min="0" max="100" step="0.1" placeholder="Contoh: 85" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                        <input class="form-control" style="border-color: #25671E;" type="date" name="grades[${entries}][assessment_date]" value="${new Date().toISOString().split('T')[0]}" required>
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-outline-danger removeGradeBtn">🗑️ Hapus Entry</button>
                    </div>
                </div>
            `;
            
            container.appendChild(newEntry);
            attachRemoveListener();
        });

        function attachRemoveListener() {
            document.querySelectorAll('.removeGradeBtn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('.grade-entry').remove();
                });
            });
        }

        attachRemoveListener();
    </script>
@endsection
