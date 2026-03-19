@extends('layouts.lms')

@section('title', 'Edit User')

@section('content')
    <h1 class="h3 mb-3">Edit User</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="roleSelect" name="role" required>
                        @foreach(['admin' => 'Admin', 'tatausaha' => 'Tata Usaha', 'guru' => 'Guru', 'siswa' => 'Siswa'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('role', $user->role) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                @if($user->role === 'guru')
                <div class="mb-3" id="nipField">
                    <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                    <input class="form-control" type="text" name="nip" value="{{ old('nip', $user->teacher?->nip) }}" placeholder="Masukkan NIP guru">
                    @error('nip')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @endif

                @if($user->role === 'siswa')
                <div class="mb-3" id="nisField">
                    <label class="form-label">NIS (Nomor Induk Siswa)</label>
                    <input class="form-control" type="text" name="nis" value="{{ old('nis', $user->student?->nis) }}" placeholder="Masukkan NIS siswa">
                    @error('nis')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password Baru (opsional)</label>
                        <input class="form-control" type="password" name="password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input class="form-control" type="password" name="password_confirmation">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const role = this.value;
            const nipField = document.getElementById('nipField');
            const nisField = document.getElementById('nisField');
            
            if (nipField) nipField.style.display = role === 'guru' ? 'block' : 'none';
            if (nisField) nisField.style.display = role === 'siswa' ? 'block' : 'none';
        });
    </script>
@endsection

