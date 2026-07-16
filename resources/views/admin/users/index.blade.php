@extends('layouts.lms')

@section('title', 'Kelola User')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">👤 Kelola User</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Manage semua pengguna sistem LMS</p>
        </div>
        <a class="btn btn-primary btn-lg" href="{{ route('admin.users.create') }}">+ Tambah User Baru</a>
    </div>

    @if($users->isEmpty())
        <div class="content-card">
            <div class="content-card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-users" style="font-size: 1.75rem; color: var(--secondary); opacity: 0.5;"></i>
                    </div>
                    <p class="empty-state-text">Belum ada user. Mulai dengan membuat user baru untuk sistem LMS.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary-theme btn-sm mt-3">+ Tambah Sekarang</a>
                </div>
            </div>
        </div>
    @else
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-header-icon">📋</div>
                <h5 class="content-card-title">Daftar Pengguna</h5>
            </div>
            <div class="content-card-body" style="padding-top: 12px;">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary);">{{ $user->name }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td>
                                    @php
                                        $roleStyle = match($user->role) {
                                            'admin' => 'background: rgba(27, 94, 32, 0.12); color: var(--primary);',
                                            'guru' => 'background: rgba(67, 160, 71, 0.12); color: #2E7D32;',
                                            'siswa' => 'background: rgba(249, 168, 37, 0.15); color: #B26A00;',
                                            'tatausaha' => 'background: rgba(0,0,0,0.05); color: var(--primary);',
                                            default => 'background: rgba(0,0,0,0.05); color: var(--text-muted);'
                                        };
                                        $roleLabel = match($user->role) {
                                            'admin' => 'Admin',
                                            'guru' => 'Guru',
                                            'siswa' => 'Siswa',
                                            'tatausaha' => 'Tata Usaha',
                                            default => $user->role
                                        };
                                    @endphp
                                    <span class="status-badge" style="{{ $roleStyle }}">{{ $roleLabel }}</span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary-theme" href="{{ route('admin.users.edit', $user) }}">✏️ Edit</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Hapus user ini?')) { document.getElementById('form-{{ $user->id }}').submit(); }" type="button">🗑️ Hapus</button>
                                    <form id="form-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
@endsection
