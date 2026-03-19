@extends('layouts.lms')

@section('title', 'Buat Tugas')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📝 Buat Tugas Baru</h1>
        <p class="text-muted mb-0">Berikan tugas baru untuk siswa Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.assignments.store') }}" enctype="multipart/form-data">
                        @csrf

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
                            <textarea class="form-control" style="border-color: #25671E;" name="description" placeholder="Jelaskan detail tugas, instruksi, dan ekspektasi siswa Anda..." rows="6">{{ old('description') }}</textarea>
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

                        <!-- File PDF Tugas -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📤 File PDF Tugas (Opsional)</label>
                            <input type="file" class="form-control" style="border-color: #25671E;" name="file" accept=".pdf">
                            <small class="text-muted">Pilih file PDF soal/instruksi tugas jika ada (Maksimal 10MB)</small>
                            @error('file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
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
                </div>
            </div>
        </div>
    </div>
@endsection

