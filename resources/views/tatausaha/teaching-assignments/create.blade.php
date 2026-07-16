@extends('layouts.lms')

@section('title', 'Tambah Penugasan Guru')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center gap-3 mb-5 reveal">
        <a href="{{ route('tatausaha.teaching-assignments.index') }}" class="btn btn-outline-secondary-theme btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 4px;">
                Tambah Penugasan Guru
            </h1>
            <p class="text-muted mb-0 small">Guru mengambil kelas untuk mengajar mata pelajaran</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="content-card reveal reveal-delay-1" style="overflow: visible;">
                <div class="content-card-header">
                    <div class="content-card-header-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h2 class="content-card-title">Form Penugasan</h2>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('tatausaha.teaching-assignments.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--primary);">Tahun Ajar</label>
                            <select class="form-select ts-select" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajar --</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" @selected(old('academic_year_id') == $ay->id)>
                                        {{ $ay->name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--primary);">Kelas</label>
                            <select class="form-select ts-select" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" @selected(old('class_id') == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--primary);">Mata Pelajaran</label>
                            <select class="form-select ts-select" name="subject_id" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" @selected(old('subject_id') == $s->id)>
                                        {{ $s->name }} ({{ $s->code ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--primary);">Guru</label>
                            <select class="form-select ts-select" name="teacher_id" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" @selected(old('teacher_id') == $t->id)>{{ $t->user->name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-5">
                            <button class="btn btn-lg btn-outline-primary-theme" type="submit">
                                <i class="fas fa-check me-1"></i> Simpan
                            </button>
                            <a class="btn btn-lg btn-outline-secondary-theme" href="{{ route('tatausaha.teaching-assignments.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-card reveal reveal-delay-2" style="border-top: 3px solid var(--accent);">
                <div class="content-card-header">
                    <div class="content-card-header-icon" style="background: linear-gradient(135deg, rgba(249, 168, 37, 0.15), rgba(249, 168, 37, 0.06)); color: var(--accent);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h2 class="content-card-title">Info</h2>
                </div>
                <div class="content-card-body">
                    <p class="small text-muted mb-0">Setelah penugasan dibuat, siswa di kelas tersebut akan melihat mata pelajaran ini di halaman Mata Pelajaran mereka.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
