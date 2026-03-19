@extends('layouts.lms')

@section('title', 'Data Siswa')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Kelola Data Siswa</h1>
        <a class="btn btn-primary" href="{{ route('tatausaha.students.create') }}">Tambah Siswa</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('tatausaha.students.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Filter Jurusan</label>
                    <select name="major" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Jurusan</option>
                        <option value="IPA" @selected(request('major') == 'IPA')>🧪 IPA</option>
                        <option value="IPS" @selected(request('major') == 'IPS')>🧪 IPS</option>
                        <option value="Bahasa" @selected(request('major') == 'Bahasa')>📖 Bahasa</option>
                        <option value="Umum" @selected(request('major') == 'Umum')>📂 Umum</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Filter Kelas</label>
                    <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }} ({{ $class->major }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('tatausaha.students.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Kelas</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->user?->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $student->schoolClass?->major == 'IPA' ? 'bg-primary' : ($student->schoolClass?->major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $student->schoolClass?->major ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $student->schoolClass?->name ?? '-' }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('tatausaha.students.edit', $student) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('tatausaha.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $students->links() }}
    </div>
@endsection

