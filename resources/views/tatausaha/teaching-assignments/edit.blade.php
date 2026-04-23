@extends('layouts.lms')

@section('title', 'Edit Penugasan Guru')

@section('content')
    <div class="mb-5">
        <h1 class="h3 mb-2">✏️ Edit Penugasan Guru</h1>
        <p class="text-muted mb-0">Ubah guru yang mengajar mapel di kelas</p>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tatausaha.teaching-assignments.update', $teaching_assignment) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">Tahun Ajar</label>
                            <select class="form-select ts-select" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajar --</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" @selected(old('academic_year_id', $teaching_assignment->academic_year_id) == $ay->id)>
                                        {{ $ay->name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">Kelas</label>
                            <select class="form-select ts-select" name="class_id" required>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" @selected(old('class_id', $teaching_assignment->class_id) == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">Mata Pelajaran</label>
                            <select class="form-select ts-select" name="subject_id" required>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" @selected(old('subject_id', $teaching_assignment->subject_id) == $s->id)>
                                        {{ $s->name }} ({{ $s->code ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">Guru</label>
                            <select class="form-select ts-select" name="teacher_id" required>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" @selected(old('teacher_id', $teaching_assignment->teacher_id) == $t->id)>{{ $t->user->name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Simpan Perubahan</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('tatausaha.teaching-assignments.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
