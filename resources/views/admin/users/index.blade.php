@extends('layouts.lms')

@section('title', 'Kelola User')

@section('content')
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <span class="p-2 rounded-3 me-2 text-white d-inline-flex align-items-center justify-content-center" style="background: var(--primary); width: 38px; height: 38px; font-size: 1.1rem; border-radius: 12px !important;">
                    <i class="fas fa-users-cog"></i>
                </span>
                Kelola User
            </h1>
            <p class="text-muted mb-0 ms-md-1" style="font-size: 0.9rem;">Manage semua pengguna sistem LMS</p>
        </div>
        <a class="btn btn-success px-4 py-2 border-0 shadow-sm d-inline-flex align-items-center gap-2" href="{{ route('admin.users.create') }}" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: 12px; font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-plus"></i> Tambah User Baru
        </a>
    </div>

    <!-- Search and Sort Filter Bar -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid rgba(27, 94, 32, 0.08) !important; background: #ffffff;">
        <div class="card-body p-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="position-relative">
                        <i class="fas fa-search text-muted position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); z-index: 5; opacity: 0.5; pointer-events: none; font-size: 0.85rem;"></i>
                        <input type="text" name="search" class="form-control py-2 border-light-subtle shadow-none" placeholder="Cari nama, email, atau role..." value="{{ request('search') }}" style="padding-left: 40px !important; border-radius: 12px; font-size: 0.9rem; background-color: #F8FAFC;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select py-2 border-light-subtle shadow-none pe-4" onchange="this.form.submit()" style="border-radius: 12px; font-size: 0.9rem; background-color: #F8FAFC;">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>🔤 Nama (A - Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>🔤 Nama (Z - A)</option>
                        <option value="latest" {{ request('sort', 'name_asc') == 'latest' ? 'selected' : '' }}>⏱️ Akun Terbaru</option>
                        <option value="earliest" {{ request('sort', 'name_asc') == 'earliest' ? 'selected' : '' }}>⏳ Akun Terlama</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100 py-2 border-0 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: var(--primary); border-radius: 12px; font-weight: 600; font-size: 0.9rem;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if(request()->filled('search') || (request()->filled('sort') && request('sort') !== 'name_asc'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary py-2 px-3 d-flex align-items-center justify-content-center" style="border-radius: 12px;" title="Reset Filter">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($users->isEmpty())
        <div class="content-card">
            <div class="content-card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-users" style="font-size: 1.75rem; color: var(--secondary); opacity: 0.5;"></i>
                    </div>
                    @if(request()->filled('search'))
                        <p class="empty-state-text">Tidak ada user ditemukan untuk pencarian "{{ request('search') }}".</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary-theme btn-sm mt-3">Reset Pencarian</a>
                    @else
                        <p class="empty-state-text">Belum ada user. Mulai dengan membuat user baru untuk sistem LMS.</p>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary-theme btn-sm mt-3">+ Tambah Sekarang</a>
                    @endif
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
                            <th class="text-end pe-3">Aksi</th>
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
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex align-items-center gap-1 flex-wrap justify-content-end">
                                        @if($user->role !== 'admin')
                                            <form method="POST" action="{{ route('admin.impersonate.start', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-action-impersonate d-inline-flex align-items-center gap-1" title="Login sebagai {{ $user->name }}">
                                                    <i class="fas fa-user-shield"></i>
                                                    <span>Impersonate</span>
                                                </button>
                                            </form>
                                        @endif
                                        <a class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" href="{{ route('admin.users.edit', $user) }}" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        <button class="btn btn-sm btn-action-delete d-inline-flex align-items-center gap-1" onclick="if(confirm('Hapus user {{ $user->name }}?')) { document.getElementById('form-{{ $user->id }}').submit(); }" type="button" title="Hapus User">
                                            <i class="fas fa-trash-alt"></i>
                                            <span>Hapus</span>
                                        </button>
                                        <form id="form-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
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

    @push('styles')
    <style>
        .btn-action-impersonate {
            background: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, 0.18);
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.28rem 0.65rem;
            transition: all 0.2s cubic-bezier(0.22, 0.61, 0.36, 1);
        }
        .btn-action-impersonate:hover {
            background: #0d6efd;
            color: #ffffff;
            border-color: #0d6efd;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }

        .btn-action-edit {
            background: rgba(27, 94, 32, 0.08);
            color: var(--primary);
            border: 1px solid rgba(27, 94, 32, 0.18);
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.28rem 0.65rem;
            transition: all 0.2s cubic-bezier(0.22, 0.61, 0.36, 1);
        }
        .btn-action-edit:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(27, 94, 32, 0.25);
        }

        .btn-action-delete {
            background: rgba(220, 53, 69, 0.08);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.18);
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.28rem 0.65rem;
            transition: all 0.2s cubic-bezier(0.22, 0.61, 0.36, 1);
        }
        .btn-action-delete:hover {
            background: #dc3545;
            color: #ffffff;
            border-color: #dc3545;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
        }
    </style>
    @endpush
@endsection
