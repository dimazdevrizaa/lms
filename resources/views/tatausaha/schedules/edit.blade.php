@extends('layouts.lms')

@section('title', 'Jadwal Kelas ' . $schoolClass->name)

@section('content')
<div class="schedule-editor-page">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <a href="{{ route('tatausaha.schedules.index', ['academic_year_id' => $selectedYearId]) }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
            </a>
            <h1 class="h3 mb-1 mt-2">📅 Jadwal Kelas {{ $schoolClass->name }}</h1>
            <p class="text-muted mb-0">
                @if($schoolClass->major)
                    <span class="badge" style="background-color: #48A111; font-size: 0.7rem;">{{ $schoolClass->major }}</span>
                @endif
                Susun jadwal mingguan untuk kelas ini
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tatausaha.schedules.print', ['academic_year_id' => $selectedYearId, 'class_id' => $schoolClass->id]) }}" 
               target="_blank" class="btn btn-sm btn-outline-danger shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak Jadwal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Grid Editor -->
    <form method="POST" action="{{ route('tatausaha.schedules.update', $schoolClass) }}" id="scheduleForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">

        <div class="card border-0 shadow-sm" style="cursor: default; border-radius: 16px !important;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 schedule-grid">
                        <thead>
                            <tr>
                                <th class="slot-header" style="width: 140px; min-width: 140px;">
                                    <div class="text-center">
                                        <i class="fas fa-clock me-1"></i> Waktu
                                    </div>
                                </th>
                                @foreach($days as $day)
                                    <th class="day-header text-center">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $slotIndex = 0; @endphp
                            @foreach($timeSlots as $slot)
                                <tr class="{{ $slot->isBreak() ? 'break-row' : 'lesson-row' }}">
                                    <!-- Kolom Waktu -->
                                    <td class="slot-label-cell">
                                        <div class="slot-label {{ $slot->isBreak() ? 'break-label' : '' }}">
                                            <div class="fw-bold small">{{ $slot->label }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($days as $day)
                                        <td class="{{ $slot->isBreak() ? 'break-cell' : 'lesson-cell' }}">
                                            @if($slot->isBreak())
                                                <div class="break-indicator">
                                                    <i class="fas fa-coffee"></i> {{ $slot->label }}
                                                </div>
                                            @else
                                                @php
                                                    $key = $day . '_' . $slot->id;
                                                    $existing = $schedules[$key] ?? null;
                                                    $fieldName = "slots[{$slotIndex}_{$loop->index}]";
                                                @endphp
                                                <input type="hidden" name="{{ $fieldName }}[day]" value="{{ $day }}">
                                                <input type="hidden" name="{{ $fieldName }}[time_slot_id]" value="{{ $slot->id }}">
                                                
                                                <select name="{{ $fieldName }}[subject_id]" 
                                                        class="form-select form-select-sm subject-select mb-1"
                                                        data-day="{{ $day }}" 
                                                        data-slot="{{ $slot->id }}"
                                                        onchange="onSubjectChange(this)">
                                                    <option value="">— Kosong —</option>
                                                    @foreach($uniqueSubjects as $subj)
                                                        <option value="{{ $subj->id }}" 
                                                                data-teacher-id="{{ $classAssignments[$subj->id]->teacher_id ?? $subj->teacher_id ?? '' }}"
                                                                @selected($existing && $existing->subject->name ?? '' == $subj->name)>
                                                            {{ $subj->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <select name="{{ $fieldName }}[teacher_id]" 
                                                        class="form-select form-select-sm teacher-select"
                                                        id="teacher_{{ $day }}_{{ $slot->id }}">
                                                    <option value="">— Guru —</option>
                                                    @foreach($teachers as $t)
                                                        <option value="{{ $t->id }}" @selected($existing && $existing->teacher_id == $t->id)>
                                                            {{ $t->user->name ?? '-' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @if(!$slot->isBreak())
                                    @php $slotIndex++; @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('tatausaha.schedules.index', ['academic_year_id' => $selectedYearId]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-lg shadow-sm" style="background: linear-gradient(135deg, #25671E, #48A111); color: white; border: none; border-radius: 12px; padding: 12px 40px;">
                <i class="fas fa-save me-2"></i> Simpan Jadwal
            </button>
        </div>
    </form>
</div>

<style>
    .schedule-editor-page .card:hover {
        transform: none !important;
    }

    .schedule-grid {
        border-collapse: collapse;
    }

    .schedule-grid th,
    .schedule-grid td {
        border: 1px solid #e9ecef !important;
        vertical-align: middle;
    }

    .slot-header {
        background: linear-gradient(135deg, #25671E, #48A111) !important;
        color: white !important;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 12px !important;
        position: sticky;
        left: 0;
        z-index: 2;
    }

    .day-header {
        background: linear-gradient(135deg, #25671E, #48A111) !important;
        color: white !important;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 12px 8px !important;
        min-width: 150px;
        letter-spacing: 0.5px;
    }

    .slot-label-cell {
        background: #f8f9fa;
        padding: 8px 12px !important;
        position: sticky;
        left: 0;
        z-index: 1;
    }

    .slot-label {
        text-align: center;
    }

    .slot-label.break-label {
        color: #F2B50B;
    }

    .lesson-cell {
        padding: 6px 8px !important;
        background: white;
        transition: background-color 0.2s;
    }

    .lesson-cell:hover {
        background-color: rgba(72, 161, 17, 0.04);
    }

    .break-row {
        background: repeating-linear-gradient(
            45deg,
            #fffbe6,
            #fffbe6 10px,
            #fff8d6 10px,
            #fff8d6 20px
        );
    }

    .break-cell {
        padding: 6px !important;
        background: rgba(242, 181, 11, 0.08);
    }

    .break-indicator {
        text-align: center;
        color: #b8860b;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px;
    }

    .subject-select {
        font-size: 0.75rem !important;
        padding: 4px 6px !important;
        border-color: #dee2e6;
        border-radius: 6px;
        transition: border-color 0.2s;
    }

    .subject-select:focus {
        border-color: #48A111;
        box-shadow: 0 0 0 2px rgba(72, 161, 17, 0.15);
    }

    .subject-select option:not([value=""]) {
        font-weight: 500;
    }

    .teacher-select {
        font-size: 0.7rem !important;
        padding: 3px 6px !important;
        border-color: #e9ecef;
        border-radius: 6px;
        color: #6c757d;
    }

    .teacher-select:focus {
        border-color: #F2B50B;
        box-shadow: 0 0 0 2px rgba(242, 181, 11, 0.15);
    }

    /* Highlight when filled */
    .subject-select.has-value {
        border-color: #48A111;
        background-color: rgba(72, 161, 17, 0.04);
    }
</style>

<script>
    // Mapping subject_id -> [teacher_ids] dari server
    const subjectTeachersMap = @json($subjectTeachersMap);
    
    // Simpan semua teacher options untuk rebuild dropdown
    const allTeachers = @json($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->user->name ?? '-'])->values());

    function onSubjectChange(selectEl) {
        const day = selectEl.dataset.day;
        const slotId = selectEl.dataset.slot;
        const teacherSelect = document.getElementById('teacher_' + day + '_' + slotId);
        if (!teacherSelect) return;

        const subjectId = selectEl.value;
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const suggestedTeacherId = selectedOption.dataset.teacherId;

        // Simpan teacher yang sedang terpilih
        const currentTeacherId = teacherSelect.value;

        // Rebuild teacher dropdown berdasarkan mapel yang dipilih
        teacherSelect.innerHTML = '<option value="">— Guru —</option>';

        if (subjectId && subjectTeachersMap[subjectId]) {
            // Hanya tampilkan guru yang mengampu mapel ini
            const allowedIds = subjectTeachersMap[subjectId];
            allTeachers.forEach(t => {
                if (allowedIds.includes(t.id)) {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.name;
                    teacherSelect.appendChild(opt);
                }
            });

            // Auto-select guru dari penugasan kelas
            if (suggestedTeacherId) {
                teacherSelect.value = suggestedTeacherId;
            } else if (allowedIds.length === 1) {
                // Jika hanya ada 1 guru, auto-select
                teacherSelect.value = allowedIds[0];
            }
        } else if (subjectId) {
            // Mapel tidak punya guru terdaftar → tampilkan semua guru
            allTeachers.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.textContent = t.name;
                teacherSelect.appendChild(opt);
            });
        }
        // Jika kosong, biarkan dropdown guru juga kosong

        // Visual feedback
        if (subjectId) {
            selectEl.classList.add('has-value');
        } else {
            selectEl.classList.remove('has-value');
        }
    }

    // Initialize: filter teacher dropdowns untuk yang sudah terisi on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.subject-select').forEach(sel => {
            if (sel.value) {
                sel.classList.add('has-value');
                // Rebuild dropdown guru sesuai mapel, tapi pertahankan nilai tersimpan
                const day = sel.dataset.day;
                const slotId = sel.dataset.slot;
                const teacherSelect = document.getElementById('teacher_' + day + '_' + slotId);
                if (!teacherSelect) return;

                const savedTeacherId = teacherSelect.value;
                const subjectId = sel.value;

                teacherSelect.innerHTML = '<option value="">— Guru —</option>';

                if (subjectTeachersMap[subjectId]) {
                    const allowedIds = subjectTeachersMap[subjectId];
                    allTeachers.forEach(t => {
                        if (allowedIds.includes(t.id)) {
                            const opt = document.createElement('option');
                            opt.value = t.id;
                            opt.textContent = t.name;
                            if (t.id == savedTeacherId) opt.selected = true;
                            teacherSelect.appendChild(opt);
                        }
                    });
                } else {
                    allTeachers.forEach(t => {
                        const opt = document.createElement('option');
                        opt.value = t.id;
                        opt.textContent = t.name;
                        if (t.id == savedTeacherId) opt.selected = true;
                        teacherSelect.appendChild(opt);
                    });
                }
            }
        });
    });
</script>
@endsection

