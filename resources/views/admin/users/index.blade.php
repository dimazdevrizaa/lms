@extends('layouts.lms')

@section('title', 'Kelola User')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">👤 Kelola User</h1>
            <p class="text-muted mb-0">Manage semua pengguna sistem LMS</p>
        </div>
        <a class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" href="{{ route('admin.users.create') }}">+ Tambah User Baru</a>
    </div>

    @if($users->isEmpty())
        <div class="alert alert-info border-top-4" style="border-top-color: #25671E;">
            <strong>ℹ️ Belum ada user</strong>
            <p class="mb-0 mt-2">Mulai dengan membuat user baru untuk sistem LMS.</p>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #F7F0F0;">
                    <tr>
                        <th style="border-left: 4px solid #25671E; color: #25671E;">👤 Nama</th>
                        <th>📧 Email</th>
                        <th>🔐 Role</th>
                        <th class="text-center">⚙️ Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <strong style="color: #25671E;">{{ $user->name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                @php
                                    $roleBgColor = match($user->role) {
                                        'admin' => '#25671E',
                                        'guru' => '#48A111',
                                        'siswa' => '#F2B50B',
                                        'tatausaha' => '#F7F0F0',
                                        default => '#ccc'
                                    };
                                    $roleTextColor = $user->role === 'tatausaha' ? '#25671E' : 'white';
                                    $roleLabel = match($user->role) {
                                        'admin' => 'Admin',
                                        'guru' => 'Guru',
                                        'siswa' => 'Siswa',
                                        'tatausaha' => 'Tata Usaha',
                                        default => $user->role
                                    };
                                @endphp
                                <span class="badge" style="background-color: {{ $roleBgColor }}; color: {{ $roleTextColor }};">{{ $roleLabel }}</span>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $user) }}">✏️ Edit</a>
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

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
@endsection

