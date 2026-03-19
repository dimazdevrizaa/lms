@extends('layouts.lms')

@section('title', 'Tambah Catatan Perilaku - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.behavior', $class) }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">✍️ Tambah Catatan Perilaku - {{ $class->name }}</h1>
        <p class="text-muted mb-0">Catat perilaku positif atau negatif siswa</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.classroom.behavior.store', $class) }}">
                        @csrf

                        <!-- Pilih Siswa -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">👤 Pilih Siswa</label>
                            <select class="form-select" style="border-color: #25671E;" name="student_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                        {{ $student->user->name }} (NIS: {{ $student->nis }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Judul Catatan -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Catatan</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Kedisiplinan Kelas" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Deskripsi Perilaku -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Deskripsi Perilaku</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="description" rows="4" placeholder="Jelaskan perilaku siswa secara detail..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Jenis Catatan -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🏷️ Jenis Catatan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typePositif" value="positif" @checked(old('type') === 'positif' || !old('type')) required>
                                <label class="form-check-label" for="typePositif">
                                    ✓ <strong style="color: #48A111;">Positif</strong> - Perilaku yang baik dan patut dicontoh
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeNegatif" value="negatif" @checked(old('type') === 'negatif') required>
                                <label class="form-check-label" for="typeNegatif">
                                    ⚠️ <strong style="color: #FF6B6B;">Negatif</strong> - Perilaku yang perlu perbaikan
                                </label>
                            </div>
                            @error('type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tanggal Kejadian -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal Kejadian</label>
                            <input class="form-control" style="border-color: #25671E;" type="date" name="date" value="{{ old('date', now()->toDateString()) }}" required>
                            @error('date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">
                                ✓ Simpan Catatan
                            </button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.classroom.behavior', $class) }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
