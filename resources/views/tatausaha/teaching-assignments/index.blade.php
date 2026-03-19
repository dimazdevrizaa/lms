@extends('layouts.lms')

@section('title', 'Penugasan Guru Mengajar')

@section('content')
<div class="teaching-assignments-page">
    <div class="mb-5">
        <h1 class="h3 mb-2">📋 Penugasan Guru Mengajar</h1>
        <p class="text-muted mb-0">Kelola guru yang mengambil kelas untuk mata pelajaran. Siswa akan melihat mapel berdasarkan penugasan ini.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm" style="background-color: #48A111; color: white; border: none;">➕ Tambah Penugasan</a>
        <form method="POST" action="{{ route('tatausaha.teaching-assignments.assign-all') }}" class="d-inline" onsubmit="return confirm('Assign seluruh guru ke kelas berdasarkan mapel & jurusan?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-success">⚡ Assign Seluruh Guru ke Kelas</button>
        </form>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('tatausaha.teaching-assignments.index') }}" class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Kelas</label>
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" @selected(request('class_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" @selected(request('subject_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Guru</label>
                    <select name="teacher_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" @selected(request('teacher_id') == $t->id)>{{ $t->user->name ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('tatausaha.teaching-assignments.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-4">
            @if($assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">Kelas</th>
                                <th style="color: #25671E; font-weight: 600;">Mata Pelajaran</th>
                                <th style="color: #25671E; font-weight: 600;">Guru</th>
                                <th style="color: #25671E; font-weight: 600; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $a)
                                <tr>
                                    <td><strong style="color: #25671E;">{{ $a->schoolClass->name }}</strong></td>
                                    <td>{{ $a->subject->name }} <span class="badge bg-light text-dark">{{ $a->subject->code ?? '' }}</span></td>
                                    <td>{{ $a->teacher->user->name ?? '-' }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('tatausaha.teaching-assignments.edit', $a) }}" class="btn btn-sm btn-outline-warning">✏️ Edit</a>
                                        <form method="POST" action="{{ route('tatausaha.teaching-assignments.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus penugasan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $assignments->links() }}</div>
            @else
                <div class="text-center py-5">
                    <p class="mb-3 text-muted small">Belum ada data penugasan</p>
                    <h5 class="mb-2">Belum ada penugasan</h5>
                    <p class="text-muted">Klik "Assign Seluruh Guru ke Kelas" untuk assign otomatis berdasarkan mapel & jurusan, atau tambah penugasan manual.</p>
                    <a href="{{ route('tatausaha.teaching-assignments.create') }}" class="btn btn-sm" style="background-color: #48A111; color: white;">Tambah Penugasan</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Perbaikan form-select dropdown arrow di halaman Penugasan Guru */
    .teaching-assignments-page .form-select,
    .teaching-assignments-page .form-select-sm {
        background-size: 14px 12px !important;
        padding-right: 1.5rem !important;
    }
</style>
@endsection
