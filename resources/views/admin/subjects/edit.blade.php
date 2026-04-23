@extends('layouts.lms')

@section('title', 'Edit Mata Pelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">✏️ Edit Mata Pelajaran</h1>
        <p class="text-muted mb-0">Perbarui informasi mata pelajaran</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Nama Mata Pelajaran</label>
                            <input class="form-control" style="border-color: #25671E;" name="name" value="{{ old('name', $subject->name) }}" placeholder="Contoh: Matematika" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Kode Mata Pelajaran -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🔖 Kode Mata Pelajaran</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="code" value="{{ old('code', $subject->code) }}" placeholder="Contoh: MTK">
                            @error('code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Jurusan -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🧪 Jurusan</label>
                            <select class="form-select" style="border-color: #25671E;" name="major" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="IPA" @selected(old('major', $subject->major) === 'IPA')​>🧪 IPA (Sains)</option>
                                <option value="IPS" @selected(old('major', $subject->major) === 'IPS')​>🧪 IPS (Sosial)</option>
                                <option value="Bahasa" @selected(old('major', $subject->major) === 'Bahasa')​>📖 Bahasa</option>
                                <option value="Umum" @selected(old('major', $subject->major) === 'Umum')​>📂 Umum (Semua Jurusan)</option>
                            </select>
                            @error('major')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>



                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Simpan Perubahan</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('admin.subjects.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-5">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Informasi</h5>
                    <ul class="small text-muted">
                        <li class="mb-3">
                            <strong style="color: #25671E;">Dibuat:</strong><br>
                            {{ $subject->created_at->translatedFormat('d F Y H:i') }}
                        </li>
                        <li class="mb-3">
                            <strong style="color: #48A111;">Terakhir Diubah:</strong><br>
                            {{ $subject->updated_at->translatedFormat('d F Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
