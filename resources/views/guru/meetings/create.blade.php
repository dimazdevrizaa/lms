@extends('layouts.lms')

@section('title', 'Buat Pertemuan')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">🗓️ Buka Sesi Pertemuan & Presensi</h1>
        <p class="text-muted mb-0">Atur jadwal, topik, dan mulai presensi untuk kelas Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.meetings.store') }}">
                        @csrf

                        <!-- Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Kelas</label>
                                <select class="form-select" style="border-color: #25671E;" name="class_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $prefilledClassId) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                                <select class="form-select" style="border-color: #25671E;" name="subject_id" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $prefilledSubjectId) == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Pertemuan Ke & Tanggal -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🔢 Pertemuan Ke-</label>
                                <input type="number" class="form-control" style="border-color: #25671E;" name="number" value="{{ old('number', $nextNumber) }}" min="1" required>
                                @error('number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                                <input type="date" class="form-control" style="border-color: #25671E;" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Judul Pertemuan -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Pertemuan</label>
                            <input class="form-control" style="border-color: #25671E;" name="title" value="{{ old('title') }}" placeholder="Contoh: Pengenalan Ekosistem Darat" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
 Kle                           @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📄 Deskripsi/Tujuan (Opsional)</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="description" placeholder="Tuliskan apa yang akan dipelajari di pertemuan ini..." rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Simpan & Mulai Sesi</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.meetings.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Tentang Pertemuan</h5>
                    <p class="small text-muted">
                        Pertemuan berfungsi sebagai wadah untuk mengelompokkan materi dan tugas sehingga lebih rapi dan mudah diakses oleh siswa.
                    </p>
                    <ul class="small text-muted">
                        <li class="mb-2">Gunakan nomor urut untuk memudahkan navigasi</li>
                        <li class="mb-2">Siswa akan melihat materi sesuai kelompok pertemuan ini</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
