@extends('layouts.lms')

@section('title', 'Buat Pertemuan')

@section('content')
    @php
        // ponytail: fallback to general meetings index if class or subject is not set
        $backUrl = isset($currentClass) && isset($currentSubject)
            ? route('guru.meetings.class-meetings', ['classSlug' => $currentClass->slug, 'subjectSlug' => $currentSubject->slug])
            : route('guru.meetings.index');
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
                    @if(isset($currentClass) && isset($currentSubject))
                        <li class="breadcrumb-item"><a href="{{ route('guru.meetings.class-meetings', ['classSlug' => $currentClass->slug, 'subjectSlug' => $currentSubject->slug]) }}" style="color: var(--primary-light); text-decoration: none;">{{ $currentClass->name }} - {{ $currentSubject->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary);">🗓️ Buka Sesi Pertemuan & Presensi</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 reveal-delay-1">
            <div class="content-card">
                <div class="content-card-header bg-white py-3">
                    <div class="content-card-header-icon" style="background-color: rgba(27, 94, 32, 0.08); color: var(--primary);">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h5 class="content-card-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Formulir Pertemuan Baru</h5>
                </div>
                <div class="content-card-body p-4">
                    <form method="POST" action="{{ route('guru.meetings.store') }}">
                        @csrf

                        <!-- Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">🎓 Kelas</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" name="class_id" required style="border-radius: 10px;">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $prefilledClassId) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📖 Mata Pelajaran</label>
                                <select class="form-select @error('subject_id') is-invalid @enderror" name="subject_id" required style="border-radius: 10px;">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $prefilledSubjectId) == $subject->id)>{{ $subject->name }}</option>
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
                                <input type="number" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number', $nextNumber) }}" min="1" required style="border-radius: 10px;">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📅 Tanggal</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', date('Y-m-d')) }}" required style="border-radius: 10px;">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Judul Pertemuan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📝 Judul Pertemuan</label>
                            <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="Contoh: Pengenalan Ekosistem Darat" required style="border-radius: 10px;">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.9rem;">📄 Deskripsi/Tujuan (Opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Tuliskan apa yang akan dipelajari di pertemuan ini..." rows="4" style="border-radius: 10px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Visibility -->
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark" for="is_visible">Tampilkan ke Siswa</label>
                            <div class="form-text text-muted small">Jika dinonaktifkan, siswa tidak akan dapat melihat pertemuan ini beserta materi dan tugas di dalamnya.</div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-success px-4 py-2" style="background-color: var(--primary); border: none; border-radius: 12px; font-weight: 600;" type="submit">
                                <i class="fas fa-save me-2"></i> Simpan & Mulai Sesi
                            </button>
                            <a class="btn btn-outline-secondary px-4 py-2" style="border-radius: 12px; font-weight: 600;" href="{{ $backUrl }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4 mb-4 reveal-delay-2">
            <div class="content-card" style="border-left: 4px solid var(--accent);">
                <div class="content-card-header bg-white py-3">
                    <div class="content-card-header-icon" style="background-color: rgba(249, 168, 37, 0.08); color: var(--accent);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h5 class="content-card-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Tentang Pertemuan</h5>
                </div>
                <div class="content-card-body p-4">
                    <p class="small text-muted mb-3">
                        Pertemuan berfungsi sebagai wadah untuk mengelompokkan materi pembelajaran dan tugas siswa agar proses belajar-mengajar terstruktur.
                    </p>
                    <hr style="border-color: rgba(37, 103, 30, 0.08);">
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Gunakan nomor urut yang sesuai (misal: Sesi 1, Sesi 2) untuk memudahkan pelacakan kurikulum.</li>
                        <li class="mb-2">Tanggal pertemuan akan digunakan sebagai acuan pencatatan kehadiran presensi siswa.</li>
                        <li class="mb-0">Setelah menyimpan, Anda dapat langsung mengisi presensi dan menambahkan file materi/tugas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
