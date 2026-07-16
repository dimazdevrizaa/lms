@extends('layouts.lms')

@section('title', 'Edit Pertemuan')

@section('content')
    @php
        // ponytail: route back to the specific class meetings index using class/subject slugs
        $backUrl = route('guru.meetings.class-meetings', ['classSlug' => $meeting->schoolClass->slug, 'subjectSlug' => $meeting->subject->slug]);
    @endphp
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-5 reveal">
        <a href="{{ $backUrl }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('guru.meetings.index') }}" style="color: var(--primary-light); text-decoration: none;">Ruang Kelas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.meetings.class-meetings', ['classSlug' => $meeting->schoolClass->slug, 'subjectSlug' => $meeting->subject->slug]) }}" style="color: var(--primary-light); text-decoration: none;">{{ $meeting->schoolClass->name }} - {{ $meeting->subject->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.meetings.show', $meeting) }}" style="color: var(--primary-light); text-decoration: none;">Detail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary);">🗓️ Edit Sesi Pertemuan</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 reveal-delay-1">
            <div class="content-card">
                <div class="content-card-header bg-white py-3">
                    <div class="content-card-header-icon" style="background-color: rgba(27, 94, 32, 0.08); color: var(--primary);">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h5 class="content-card-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Formulir Perbarui Pertemuan</h5>
                </div>
                <div class="content-card-body p-4">
                    <form method="POST" action="{{ route('guru.meetings.update', $meeting) }}">
                        @csrf
                        @method('PUT')

                        <!-- Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">🎓 Kelas</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" name="class_id" required style="border-radius: 10px;">
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $meeting->class_id) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📖 Mata Pelajaran</label>
                                <select class="form-select @error('subject_id') is-invalid @enderror" name="subject_id" required style="border-radius: 10px;">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $meeting->subject_id) == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pertemuan Ke & Tanggal -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">🔢 Pertemuan Ke-</label>
                                <input type="number" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number', $meeting->number) }}" min="1" required style="border-radius: 10px;">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📅 Tanggal</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $meeting->date) }}" required style="border-radius: 10px;">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Judul Pertemuan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📝 Judul Pertemuan</label>
                            <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $meeting->title) }}" required style="border-radius: 10px;">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📄 Deskripsi/Tujuan (Opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" style="border-radius: 10px;">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Visibility -->
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $meeting->is_visible) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark" for="is_visible">Tampilkan ke Siswa</label>
                            <div class="form-text text-muted small">Jika dinonaktifkan, siswa tidak akan dapat melihat pertemuan ini beserta materi dan tugas di dalamnya.</div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-success px-4 py-2" style="background-color: var(--primary); border: none; border-radius: 12px; font-weight: 600;" type="submit">
                                <i class="fas fa-check-circle me-2"></i> Perbarui Pertemuan
                            </button>
                            <a class="btn btn-outline-secondary px-4 py-2" style="border-radius: 12px; font-weight: 600;" href="{{ $backUrl }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
