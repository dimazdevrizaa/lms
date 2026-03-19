@extends('layouts.lms')

@section('title', 'Edit Materi')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📚 Edit Materi Pembelajaran</h1>
        <p class="text-muted mb-0">Perbarui konten materi pembelajaran Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.materials.update', $material) }}" enctype="multipart/form-data">
                        <!-- Pertemuan, Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-12 mb-4">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🗓️ Pilih Pertemuan (Opsional)</label>
                                <select class="form-select" style="border-color: #F2B50B; border-width: 2px;" name="meeting_id" id="meeting_id">
                                    <option value="">-- Tanpa Pertemuan (Materi Mandiri) --</option>
                                    @foreach($meetings as $meeting)
                                        <option value="{{ $meeting->id }}" 
                                            data-class="{{ $meeting->class_id }}" 
                                            data-subject="{{ $meeting->subject_id }}"
                                            @selected(old('meeting_id', $material->meeting_id) == $meeting->id)>
                                            Pertemuan {{ $meeting->number }}: {{ $meeting->title }} ({{ $meeting->schoolClass->name }} - {{ $meeting->subject->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Kelas</label>
                                <select class="form-select" style="border-color: #25671E;" name="class_id" id="class_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $material->class_id) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                                <select class="form-select" style="border-color: #25671E;" name="subject_id" id="subject_id" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $material->subject_id) == $subject->id || $subjects->count() == 1)>{{ $subject->name }}</option>
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

                                function updateFields() {
                                    const selectedOption = meetingSelect.options[meetingSelect.selectedIndex];
                                    if (selectedOption.value) {
                                        const classId = selectedOption.getAttribute('data-class');
                                        const subjectId = selectedOption.getAttribute('data-subject');
                                        if (classId) classSelect.value = classId;
                                        if (subjectId) subjectSelect.value = subjectId;
                                    }
                                }

                                meetingSelect.addEventListener('change', updateFields);
                            });
                        </script>

                        <!-- Judul Materi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Materi</label>
                            <input class="form-control" style="border-color: #25671E;" name="title" value="{{ old('title', $material->title) }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Konten -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📄 Konten Materi (Opsional)</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="content" rows="6">{{ old('content', $material->content) }}</textarea>
                            @error('content')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- File PDF -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📤 File PDF Materi</label>
                            @if($material->file_path)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf me-1"></i> Lihat PDF Saat Ini
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" style="border-color: #25671E;" name="file" accept=".pdf">
                            <small class="text-muted">Pilih file PDF baru jika ingin mengganti file lama (Maksimal 10MB)</small>
                            @error('file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Update Materi</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.materials.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
