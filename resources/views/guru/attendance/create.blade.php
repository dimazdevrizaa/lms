@extends('layouts.lms')

@section('title', 'Input Absensi')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">✅ Input Kehadiran Siswa</h1>
        @if(isset($meeting))
            <p class="text-muted mb-0">Pertemuan ke-{{ $meeting->number }}: {{ $meeting->title }} | {{ $meeting->schoolClass->name }}</p>
        @else
            <p class="text-muted mb-0">Catat kehadiran siswa untuk kelas dan mata pelajaran tertentu</p>
        @endif
    </div>

    <form method="POST" action="{{ route('guru.attendances.store') }}">
        @csrf
        @if(isset($meeting))
            <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Konfigurasi</h5>
                        
                        <!-- Kelas -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Kelas</label>
                            <select class="form-select @if(isset($meeting)) bg-light @endif" style="border-color: #25671E;" name="class_id" id="classSelect" required @if(isset($meeting)) readonly @endif>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('class_id', isset($meeting) ? $meeting->class_id : null) == $class->id)>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                            <select class="form-select @if(isset($meeting)) bg-light @endif" style="border-color: #25671E;" name="subject_id" required @if(isset($meeting)) readonly @endif>
                                @if($subjects->count() > 1 && !isset($meeting))
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                @endif
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id', isset($meeting) ? $meeting->subject_id : null) == $subject->id || $subjects->count() == 1)>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                            <input class="form-control @if(isset($meeting)) bg-light @endif" style="border-color: #25671E;" type="date" name="date" value="{{ old('date', isset($meeting) ? $meeting->date : now()->toDateString()) }}" required @if(isset($meeting)) readonly @endif>
                            @error('date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-lg" style="background-color: #25671E; color: white; border: none;" type="submit">
                        <i class="fas fa-save me-2"></i> Simpan Absensi
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ isset($meeting) ? route('guru.meetings.show', $meeting) : route('guru.attendances.index') }}">Batal</a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Daftar Siswa</h5>
                            <button type="button" class="btn btn-sm btn-outline-success" id="markAllHadir">
                                <i class="fas fa-check-double me-1"></i> Semua Hadir
                            </button>
                        </div>

                        <div id="studentContainer">
                            <div class="text-center py-5 text-muted">
                                <p class="mb-2" style="font-size: 1.5rem; opacity: 0.5;">👆</p>
                                <p>Silakan pilih kelas terlebih dahulu</p>
                            </div>
                        </div>

                        <div class="table-responsive d-none" id="studentTableWrapper">
                            <table class="table table-hover align-middle">
                                <thead style="background-color: #F7F0F0;">
                                    <tr>
                                        <th style="color: #25671E; width: 50px;">No</th>
                                        <th style="color: #25671E;">Nama Siswa</th>
                                        <th style="color: #25671E; text-align: center; width: 280px;">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                    <!-- Students will be loaded here via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .status-radio-group {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .status-radio-item {
            flex: 1;
        }
        .status-radio-item input[type="radio"] {
            display: none;
        }
        .status-radio-item label {
            display: block;
            padding: 5px 2px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        /* Hadir */
        .status-radio-item input[value="hadir"]:checked + label {
            background-color: #25671E;
            color: white;
            border-color: #25671E;
        }
        /* Izin */
        .status-radio-item input[value="izin"]:checked + label {
            background-color: #F2B50B;
            color: #25671E;
            border-color: #F2B50B;
        }
        /* Sakit */
        .status-radio-item input[value="sakit"]:checked + label {
            background-color: #48A111;
            color: white;
            border-color: #48A111;
        }
        /* Alpa */
        .status-radio-item input[value="alpa"]:checked + label {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .status-radio-item label:hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        const classesData = @json($classes);
        const studentStats = @json($studentStats);
        const classSelect = document.getElementById('classSelect');
        const studentContainer = document.getElementById('studentContainer');
        const studentTableWrapper = document.getElementById('studentTableWrapper');
        const studentTableBody = document.getElementById('studentTableBody');
        const markAllHadirBtn = document.getElementById('markAllHadir');

        function renderStats(studentId) {
            const stats = studentStats[studentId];
            if (!stats) return '';
            
            let html = '';
            if (stats.hadir) html += `<span class="badge bg-light text-primary border me-1" title="Total Hadir">${stats.hadir} H</span>`;
            if (stats.izin) html += `<span class="badge bg-light text-warning border me-1" title="Total Izin">${stats.izin} I</span>`;
            if (stats.sakit) html += `<span class="badge bg-light text-info border me-1" title="Total Sakit">${stats.sakit} S</span>`;
            if (stats.alpa) html += `<span class="badge bg-light text-danger border me-1" title="Total Alpa">${stats.alpa} A</span>`;
            
            return html ? `<small class="text-muted" style="font-size: 10px;">Rekap: </small>${html}` : '';
        }

        function loadStudents(classId) {
            if (!classId) {
                studentContainer.classList.remove('d-none');
                studentTableWrapper.classList.add('d-none');
                return;
            }

            const selectedClass = classesData.find(c => c.id == classId);
            if (selectedClass && selectedClass.students) {
                studentContainer.classList.add('d-none');
                studentTableWrapper.classList.remove('d-none');
                studentTableBody.innerHTML = '';

                selectedClass.students.forEach((student, index) => {
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <strong>${student.user.name}</strong><br>
                                <small class="text-muted">NIS: ${student.nis}</small>
                                <div class="mt-1">
                                    ${renderStats(student.id)}
                                </div>
                            </td>
                            <td>
                                <div class="status-radio-group">
                                    <div class="status-radio-item">
                                        <input type="radio" name="statuses[${student.id}]" id="h_${student.id}" value="hadir" checked required>
                                        <label for="h_${student.id}">HADIR</label>
                                    </div>
                                    <div class="status-radio-item">
                                        <input type="radio" name="statuses[${student.id}]" id="i_${student.id}" value="izin" required>
                                        <label for="i_${student.id}">IZIN</label>
                                    </div>
                                    <div class="status-radio-item">
                                        <input type="radio" name="statuses[${student.id}]" id="s_${student.id}" value="sakit" required>
                                        <label for="s_${student.id}">SAKIT</label>
                                    </div>
                                    <div class="status-radio-item">
                                        <input type="radio" name="statuses[${student.id}]" id="a_${student.id}" value="alpa" required>
                                        <label for="a_${student.id}">ALPA</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    studentTableBody.insertAdjacentHTML('beforeend', row);
                });
            }
        }

        classSelect.addEventListener('change', (e) => loadStudents(e.target.value));

        markAllHadirBtn.addEventListener('click', () => {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="hadir"]');
            radioButtons.forEach(radio => radio.checked = true);
        });

        // Initialize if old class_id exists
        if (classSelect.value) {
            loadStudents(classSelect.value);
        }
    </script>
@endsection

