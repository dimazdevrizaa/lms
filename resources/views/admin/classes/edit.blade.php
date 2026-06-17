@extends('layouts.lms')

@section('title', 'Edit Kelas')

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-0">Edit Kelas</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Kelas</label>
                    <input class="form-control" name="name" value="{{ old('name', $class->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tingkat (X / XI / XII)</label>
                    <input class="form-control" name="level" value="{{ old('level', $class->level) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select class="form-select" name="major" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="IPA" @selected(old('major', $class->major) === 'IPA')​>IPA</option>
                        <option value="IPS" @selected(old('major', $class->major) === 'IPS')​>IPS</option>
                        <option value="Bahasa" @selected(old('major', $class->major) === 'Bahasa')​>Bahasa</option>
                        <option value="Umum" @selected(old('major', $class->major) === 'Umum')​>Umum</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select class="form-select" name="academic_year_id">
                        <option value="">-</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" @selected(old('academic_year_id', $class->academic_year_id) == $year->id)>
                                {{ $year->name }}{{ $year->is_active ? ' (aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Wali Kelas</label>
                    <select class="form-select" name="homeroom_teacher_id">
                        <option value="">-</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id)>
                                {{ $teacher->user?->name ?? 'Guru #' . $teacher->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

