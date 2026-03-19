@extends('layouts.lms')

@section('title', 'Tambah User')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">👤 Tambah User Baru</h1>
        <p class="text-muted mb-0">Buat akun pengguna baru untuk sistem LMS</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Nama User -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">👤 Nama Lengkap</label>
                            <input class="form-control" style="border-color: #25671E;" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📧 Email</label>
                            <input class="form-control" style="border-color: #25671E;" type="email" name="email" value="{{ old('email') }}" placeholder="example@school.com" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🔐 Peran (Role)</label>
                            <select class="form-select" style="border-color: #25671E;" id="roleSelect" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach(['admin' => '📄 Admin', 'tatausaha' => '🃋 Tata Usaha', 'guru' => '👨‍🏫 Guru', 'siswa' => '🎒 Siswa'] as $key => $label)
                                    <option value="{{ $key }}" @selected(old('role') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- NIP (untuk guru) -->
                        <div class="mb-4" id="nipField" style="display: none;">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🆔 NIP (Nomor Induk Pegawai)</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP guru">
                            @error('nip')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- NIS (untuk siswa) -->
                        <div class="mb-4" id="nisField" style="display: none;">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🆔 NIS (Nomor Induk Siswa)</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS siswa">
                            @error('nis')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- NIP (untuk guru) -->
                        <div class="mb-4" id="nipField" style="display: none;">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🆔 NIP (Nomor Induk Pegawai)</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP guru">
                            @error('nip')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- NIS (untuk siswa) -->
                        <div class="mb-4" id="nisField" style="display: none;">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">🆔 NIS (Nomor Induk Siswa)</label>
                            <input class="form-control" style="border-color: #25671E;" type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS siswa">
                            @error('nis')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password Fields -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🔄 Password</label>
                                <input class="form-control" style="border-color: #25671E;" type="password" name="password" placeholder="Masukkan password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">✅ Konfirmasi Password</label>
                                <input class="form-control" style="border-color: #25671E;" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Buat User</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('admin.users.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-5">
            <div class="card" style="border-top: 4px solid #F2B50B;">
                <div class="card-body">
                    <h5 class="card-title mb-3">💡 Panduan Role</h5>
                    <ul class="small text-muted">
                        <li class="mb-3">
                            <strong style="color: #25671E;">📄 Admin</strong><br>
                            Akses penuh ke semua fitur sistem
                        </li>
                        <li class="mb-3">
                            <strong style="color: #48A111;">🃋 Tata Usaha</strong><br>
                            Mengelola data siswa, guru, dan kelas
                        </li>
                        <li class="mb-3">
                            <strong style="color: #25671E;">👨‍🏫 Guru</strong><br>
                            Membuat tugas, materi, dan penilaian
                        </li>
                        <li>
                            <strong style="color: #F2B50B;">🎒 Siswa</strong><br>
                            Mengumpulkan tugas dan melihat nilai
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const role = this.value;
            document.getElementById('nipField').style.display = role === 'guru' ? 'block' : 'none';
            document.getElementById('nisField').style.display = role === 'siswa' ? 'block' : 'none';
            
            // Reset values when toggling
            if (role !== 'guru') document.querySelector('input[name="nip"]').value = '';
            if (role !== 'siswa') document.querySelector('input[name="nis"]').value = '';
        });
        
        // Show field jika sudah ada nilai sebelumnya (reload form dengan error)
        window.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            if (roleSelect.value === 'guru') {
                document.getElementById('nipField').style.display = 'block';
            } else if (roleSelect.value === 'siswa') {
                document.getElementById('nisField').style.display = 'block';
            }
        });
    </script>
@endsection

