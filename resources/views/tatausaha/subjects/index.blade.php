@extends('layouts.lms')

@section('title', 'Mata Pelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📚 Daftar Mata Pelajaran</h1>
        <p class="text-muted mb-0">Kelola semua mata pelajaran di sekolah</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tambah Mata Pelajaran Button -->
    <div class="mb-4">
        <a href="{{ route('tatausaha.subjects.create') }}" class="btn btn-sm" style="background-color: #48A111; color: white; border: none;">
            ➕ Tambah Mata Pelajaran
        </a>
    </div>

    <!-- Tabel Mata Pelajaran -->
    <div class="card">
        <div class="card-body p-4">
            @if($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">📖 Nama Mata Pelajaran</th>
                                <th style="color: #25671E; font-weight: 600;">🔖 Kode</th>
                                <th style="color: #25671E; font-weight: 600;">🧪 Jurusan</th>
                                <th style="color: #25671E; font-weight: 600;">👨‍🏫 Guru</th>
                                <th style="color: #25671E; font-weight: 600; text-align: center;">⚙️ Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>
                                        <strong style="color: #25671E;">{{ $subject->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #F2B50B; color: #25671E;">
                                            {{ $subject->code ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $subject->major == 'IPA' ? 'bg-primary' : ($subject->major == 'IPS' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ $subject->major ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($subject->teacher)
                                            <small class="text-muted">{{ $subject->teacher->user->name ?? '—' }}</small>
                                        @else
                                            <small class="text-muted">—</small>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('tatausaha.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-warning">
                                            ✏️ Edit
                                        </a>
                                        <form method="POST" action="{{ route('tatausaha.subjects.destroy', $subject) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $subjects->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">📭 Belum ada mata pelajaran. <a href="{{ route('tatausaha.subjects.create') }}">Tambah sekarang</a></p>
                </div>
            @endif
        </div>
    </div>
@endsection
