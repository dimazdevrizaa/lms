@extends('layouts.lms')

@section('title', 'Edit Pertemuan')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">🗓️ Edit Pertemuan</h1>
        <p class="text-muted mb-0">Perbarui informasi pertemuan Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('guru.meetings.update', $meeting) }}">
                        @csrf
                        @method('PUT')

                        <!-- Kelas & Mata Pelajaran -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">🎓 Kelas</label>
                                <select class="form-select" style="border-color: #25671E;" name="class_id" required>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $meeting->class_id) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📖 Mata Pelajaran</label>
                                <select class="form-select" style="border-color: #25671E;" name="subject_id" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $meeting->subject_id) == $subject->id)>{{ $subject->name }}</option>
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
                                <input type="number" class="form-control" style="border-color: #25671E;" name="number" value="{{ old('number', $meeting->number) }}" min="1" required>
                                @error('number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                                <input type="date" class="form-control" style="border-color: #25671E;" name="date" value="{{ old('date', $meeting->date) }}" required>
                                @error('date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Judul Pertemuan -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📝 Judul Pertemuan</label>
                            <input class="form-control" style="border-color: #25671E;" name="title" value="{{ old('title', $meeting->title) }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📄 Deskripsi/Tujuan (Opsional)</label>
                            <textarea class="form-control" style="border-color: #25671E;" name="description" rows="4">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" type="submit">✓ Perbarui Pertemuan</button>
                            <a class="btn btn-lg btn-outline-secondary" href="{{ route('guru.meetings.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
