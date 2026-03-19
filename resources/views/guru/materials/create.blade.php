@extends('layouts.lms')

@section('title', 'Upload Materi')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📚 Upload Materi Pembelajaran Baru</h1>
        <p class="text-muted mb-0">Bagikan materi pembelajaran kepada siswa Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Pertemuan, Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-12 mb-4">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🗓️ Pilih Pertemuan (Opsional)</label>
                                <select class="form-select select-meeting" style="border-color: #F2B50B; border-width: 2px;" name="meeting_id" id="meeting_id">
                                    <option value="">-- Tanpa Pertemuan (Materi Mandiri) --</option>
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
                                <select class="form-select" style="border-color: #25671E;" name="class_id" id="class_id" required>
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
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                                <select class="form-select" style="border-color: #25671E;" name="subject_id" id="subject_id" required>
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
                                        
                                        // Optional: Disable while meeting is selected to prevent mismatch
                                        // classSelect.disabled = true;
                                        // subjectSelect.disabled = true;
                                    } else {
                                        // classSelect.disabled = false;
                                        // subjectSelect.disabled = false;
                                    }
                                }

                                meetingSelect.addEventListener('change', updateFields);
                                
                                // Run on load in case of pre-selected meeting
                                if (meetingSelect.value) {
                                    updateFields();
                                }
                            });
                        </script>

                        <!-- Judul Materi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Materi</label>
                            <input class="form-control" style="border-color: #25671E;" name="title" value="{{ old('title') }}" placeholder="Contoh: Bab 5 - Persamaan Kuadrat" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Konten -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📄 Konten Materi (Opsional)</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="content" placeholder="Tuliskan konten materi pembelajaran di sini..." rows="6">{{ old('content') }}</textarea>
                            <small class="text-muted">Anda dapat menuliskan teks materi secara langsung di sini</small>
                            @error('content')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- File PDF -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📤 File PDF Materi</label>
                            <input type="file" class="form-control" style="border-color: #25671E;" name="file" accept=".pdf">
                            <small class="text-muted">Pilih file PDF materi jika ada (Maksimal 10MB)</small>
                            @error('file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Upload Materi</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.materials.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Tips Upload Materi</h5>
                    <ul class="small text-muted">
                        <li class="mb-2">Gunakan judul yang deskriptif dan mudah dipahami</li>
                        <li class="mb-2">Jelaskan tujuan dan topik pembelajaran</li>
                        <li class="mb-2">Gunakan format yang rapi dan terstruktur</li>
                        <li class="mb-2">Tambahkan referensi atau sumber bacaan</li>
                        <li class="mb-2">Pastikan konten sesuai dengan kurikulum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

