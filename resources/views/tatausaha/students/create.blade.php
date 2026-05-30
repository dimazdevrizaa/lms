@extends('layouts.lms')

@section('title', 'Tambah Siswa')

@section('content')
    <h1 class="h3 mb-3">Tambah Siswa</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tatausaha.students.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIS</label>
                    <input class="form-control" name="nis" value="{{ old('nis') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select class="form-select" name="class_id">
                        <option value="">-</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('tatausaha.students.index') }}">Kembali</a>
                </div>
            </form>
            <p class="text-muted mt-3 mb-0">Password default akun siswa: <strong>password</strong></p>
        </div>
    </div>
@endsection

