@extends('layouts.lms')

@section('title', 'Input Absensi')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 reveal">
        <div class="d-flex align-items-center gap-3">
            @php
                $backUrl = isset($meeting) 
                    ? route('guru.meetings.show', $meeting->id) 
                    : route('guru.attendances.index');
            @endphp
            <a href="{{ $backUrl }}" class="btn btn-outline-secondary-theme btn-sm" style="border-radius: var(--radius-sm);">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <div>
                <h1 class="mb-1 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.75rem;">✅ Input Kehadiran Siswa</h1>
                @if(isset($meeting))
                    <p class="text-muted mb-0">Pertemuan ke-{{ $meeting->number }}: {{ $meeting->title }} | {{ $meeting->schoolClass->name }}</p>
                @else
                    <p class="text-muted mb-0">Catat kehadiran siswa untuk kelas dan mata pelajaran tertentu</p>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('guru.attendances.store') }}">
        @csrf
        @if(isset($meeting))
            <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
        @endif

        <div class="row">
            <!-- Left Config Sidebar -->
            <div class="col-lg-4 mb-4 reveal reveal-delay-1">
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <div class="content-card-header-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="content-card-title mb-0">Konfigurasi</h5>
                    </div>
                    <div class="content-card-body">
                        <!-- Kelas -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--primary);">🎓 Kelas</label>
                            <select class="form-select @if(isset($meeting)) bg-light @endif" name="class_id" id="classSelect" required @if(isset($meeting)) readonly @endif style="border-radius: var(--radius-sm);">
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
                            <label class="form-label fw-bold" style="color: var(--primary);">📖 Mata Pelajaran</label>
                            <select class="form-select @if(isset($meeting)) bg-light @endif" name="subject_id" required @if(isset($meeting)) readonly @endif style="border-radius: var(--radius-sm);">
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
                            <label class="form-label fw-bold" style="color: var(--primary);">📅 Tanggal</label>
                            <input class="form-control @if(isset($meeting)) bg-light @endif" type="date" name="date" value="{{ old('date', isset($meeting) ? $meeting->date : now()->toDateString()) }}" required @if(isset($meeting)) readonly @endif style="border-radius: var(--radius-sm);">
                            @error('date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-lg btn-primary" style="border-radius: var(--radius-md);" type="submit">
                        <i class="fas fa-save me-2"></i> Simpan Absensi
                    </button>
                    <a class="btn btn-outline-secondary-theme btn-lg" href="{{ $backUrl }}" style="border-radius: var(--radius-md);">Batal</a>
                </div>
            </div>

            <!-- Right Student List -->
            <div class="col-lg-8 mb-4 reveal reveal-delay-2">
                <div class="content-card">
                    <div class="content-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="content-card-header-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="content-card-title mb-0">Daftar Siswa</h5>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary-theme" id="markAllHadir" style="border-radius: var(--radius-sm);">
                            <i class="fas fa-check-double me-1"></i> Semua Hadir
                        </button>
                    </div>
                    <div class="content-card-body">
                        <div id="studentContainer">
                            <div class="text-center py-5 text-muted">
                                <p class="mb-2" style="font-size: 1.5rem; opacity: 0.5;">👆</p>
                                <p>Silakan pilih kelas terlebih dahulu</p>
                            </div>
                        </div>

                        <div class="table-responsive d-none" id="studentTableWrapper">
                             <!-- Search Input for Students -->
                             <div class="mb-3">
                                 <div class="input-group">
                                     <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: var(--radius-sm) 0 0 var(--radius-sm); border-color: rgba(27,94,32,0.12);">
                                         <i class="fas fa-search"></i>
                                     </span>
                                     <input type="text" id="studentSearch" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa..." style="border-radius: 0 var(--radius-sm) var(--radius-sm) 0; border-color: rgba(27,94,32,0.12);">
                                 </div>
                             </div>
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem; font-weight: 700; width: 60px;">No</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem; font-weight: 700;">Nama Siswa</th>
                                        <th class="text-muted text-uppercase text-center" style="font-size: 0.75rem; font-weight: 700; width: 300px;">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                    <!-- Students loaded via JS -->
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
            gap: 6px;
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
            padding: 8px 4px;
            text-align: center;
            border: 1px solid rgba(27, 94, 32, 0.1);
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.2s cubic-bezier(0.22, 0.61, 0.36, 1);
            background: var(--bg-body);
            color: var(--text-muted);
        }
        
        /* Hadir */
        .status-radio-item input[value="hadir"]:checked + label {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(27, 94, 32, 0.15);
        }
        /* Izin */
        .status-radio-item input[value="izin"]:checked + label {
            background-color: var(--accent);
            color: #4E3400;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(249, 168, 37, 0.15);
        }
        /* Sakit */
        .status-radio-item input[value="sakit"]:checked + label {
            background-color: var(--secondary);
            color: white;
            border-color: var(--secondary);
            box-shadow: 0 4px 12px rgba(67, 160, 71, 0.15);
        }
        /* Alpa */
        .status-radio-item input[value="alpa"]:checked + label {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }
        /* Cabut */
        .status-radio-item input[value="cabut"]:checked + label {
            background-color: #fd7e14;
            color: white;
            border-color: #fd7e14;
            box-shadow: 0 4px 12px rgba(253, 126, 20, 0.15);
        }
        
        .status-radio-item label:hover {
            background-color: rgba(27, 94, 32, 0.04);
            transform: translateY(-1px);
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
            if (stats.hadir) html += `<span class="status-badge status-badge--hadir me-1" style="font-size: 0.65rem; padding: 2px 8px;" title="Total Hadir">${stats.hadir} H</span>`;
            if (stats.izin) html += `<span class="status-badge status-badge--izin me-1" style="font-size: 0.65rem; padding: 2px 8px;" title="Total Izin">${stats.izin} I</span>`;
            if (stats.sakit) html += `<span class="status-badge status-badge--sakit me-1" style="font-size: 0.65rem; padding: 2px 8px;" title="Total Sakit">${stats.sakit} S</span>`;
            if (stats.alpa) html += `<span class="status-badge status-badge--alpa me-1" style="font-size: 0.65rem; padding: 2px 8px;" title="Total Alpa">${stats.alpa} A</span>`;
            if (stats.cabut) html += `<span class="status-badge status-badge--cabut me-1" style="font-size: 0.65rem; padding: 2px 8px;" title="Total Cabut">${stats.cabut} C</span>`;
            
            return html ? `<small class="text-muted" style="font-size: 10px;">Rekap: </small>${html}` : '';
        }

        function loadStudents(classId) {
            const studentSearchEl = document.getElementById('studentSearch');
            if (studentSearchEl) {
                studentSearchEl.value = '';
            }
            if (!classId) {
                studentContainer.classList.remove('d-none');
                studentTableWrapper.classList.add('d-none');
                return;
            }

            const selectedClass = classesData.find(c => c.id == classId);
            if (selectedClass && selectedClass.students) {
                const sortedStudents = [...selectedClass.students].sort((a, b) => {
                    const nameA = (a.user && a.user.name) ? a.user.name.toLowerCase() : '';
                    const nameB = (b.user && b.user.name) ? b.user.name.toLowerCase() : '';
                    return nameA.localeCompare(nameB, undefined, { sensitivity: 'base' });
                });

                studentContainer.classList.add('d-none');
                studentTableWrapper.classList.remove('d-none');
                studentTableBody.innerHTML = '';

                sortedStudents.forEach((student, index) => {
                    const row = `
                        <tr>
                            <td class="text-muted fw-bold">${index + 1}</td>
                            <td>
                                <div class="fw-bold text-dark">${student.user ? student.user.name : 'Siswa Tanpa Akun User'}</div>
                                <div class="text-muted" style="font-size: 0.8rem;">NIS: ${student.nis}</div>
                                <div class="mt-2 d-flex align-items-center flex-wrap gap-1">
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
                                    <div class="status-radio-item">
                                        <input type="radio" name="statuses[${student.id}]" id="c_${student.id}" value="cabut" required>
                                        <label for="c_${student.id}">CABUT</label>
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

        const studentSearch = document.getElementById('studentSearch');
        if (studentSearch) {
            studentSearch.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
            studentSearch.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                const rows = studentTableBody.querySelectorAll('tr');
                rows.forEach(row => {
                    const nameCell = row.querySelector('.fw-bold.text-dark');
                    if (nameCell) {
                        const name = nameCell.textContent.toLowerCase();
                        if (name.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        }

        // Initialize if old class_id exists
        if (classSelect.value) {
            loadStudents(classSelect.value);
        }
    </script>
@endsection
