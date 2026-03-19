@extends('layouts.lms')

@section('title', 'Kelola Kelas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">🎓 Kelola Kelas</h1>
            <p class="text-muted mb-0">Manage semua kelas yang tersedia</p>
        </div>
        <a class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" href="{{ route('admin.classes.create') }}">+ Tambah Kelas</a>
    </div>

    @if($classes->isEmpty())
        <div class="alert alert-info border-top-4" style="border-top-color: #25671E;">
            <strong>ℹ️ Belum ada kelas</strong>
            <p class="mb-0 mt-2">Mulai dengan membuat kelas baru untuk tahun ajaran ini.</p>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #F7F0F0;">
                    <tr>
                        <th style="border-left: 4px solid #25671E; color: #25671E;">🎓 Nama Kelas</th>
                        <th>📆 Tingkat</th>
                        <th>🧪 Jurusan</th>
                        <th>📅 Tahun Ajaran</th>
                        <th>👨‍🏫 Wali Kelas</th>
                        <th class="text-center">⚙️ Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>
                                <strong style="color: #25671E;">{{ $class->name }}</strong>
                            </td>
                            <td>
                                <span class="badge" style="background-color: #48A111;">{{ $class->level ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $class->major == 'IPA' ? 'bg-primary' : ($class->major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ $class->major ?? '-' }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $class->academicYear?->name ?? '-' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $class->homeroomTeacher?->user?->name ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.classes.edit', $class) }}">✏️ Edit</a>
                                <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Hapus kelas ini?')) { document.getElementById('form-{{ $class->id }}').submit(); }" type="button">🗑️ Hapus</button>
                                <form id="form-{{ $class->id }}" method="POST" action="{{ route('admin.classes.destroy', $class) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    @endif
@endsection

