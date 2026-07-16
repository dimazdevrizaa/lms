@extends('layouts.lms')

@section('title', 'Data Guru')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 reveal">
        <div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--primary); margin-bottom: 4px;">
                Kelola Data Guru
            </h1>
            <p class="text-muted mb-0 small">Manajemen data guru aktif</p>
        </div>
        <a class="btn btn-outline-primary-theme" href="{{ route('tatausaha.teachers.create') }}">
            <i class="fas fa-plus me-1"></i> Tambah Guru
        </a>
    </div>

    {{-- Data Table --}}
    <div class="content-card reveal reveal-delay-1">
        <div class="content-card-header">
            <div class="content-card-header-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h2 class="content-card-title">Daftar Guru</h2>
        </div>
        <div class="content-card-body" style="padding: 0 0 8px;">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIP</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td><strong>{{ $teacher->user?->name ?? '-' }}</strong></td>
                            <td>{{ $teacher->user?->email ?? '-' }}</td>
                            <td>
                                <code style="background: rgba(27, 94, 32, 0.06); color: var(--primary); padding: 2px 8px; border-radius: 6px; font-size: 0.85rem;">
                                    {{ $teacher->nip ?? '-' }}
                                </code>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('tatausaha.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary-theme">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tatausaha.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
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
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="empty-state-text">Belum ada data guru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $teachers->links() }}
    </div>
@endsection
