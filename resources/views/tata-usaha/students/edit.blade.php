@extends('layouts.lms')

@section('title', 'Edit Siswa')

@section('content')
    <h1 class="h3 mb-3">Edit Siswa</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tatausaha.students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $student->user?->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $student->user?->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIS</label>
                    <input class="form-control" name="nis" value="{{ old('nis', $student->nis) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select class="form-select" name="class_id">
                        <option value="">-</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Ganti Password (Kosongkan jika tidak ingin ganti)</label>
                    <input class="form-control" type="password" name="password">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update Data</button>
                    <a class="btn btn-outline-secondary" href="{{ route('tatausaha.students.index') }}">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
