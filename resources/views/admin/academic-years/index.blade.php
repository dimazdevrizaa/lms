@extends('layouts.lms')

@section('title', 'Kelola Tahun Ajaran')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">📅 Kelola Tahun Ajaran</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Manage semua tahun ajaran yang tersedia</p>
        </div>
        <a class="btn btn-primary btn-lg" href="{{ route('admin.academic-years.create') }}">+ Tambah Tahun Ajaran</a>
    </div>

    @if($years->isEmpty())
        <div class="content-card">
            <div class="content-card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-alt" style="font-size: 1.75rem; color: var(--secondary); opacity: 0.5;"></i>
                    </div>
                    <p class="empty-state-text">Belum ada tahun ajaran. Mulai dengan membuat tahun ajaran baru untuk sistem LMS.</p>
                    <a href="{{ route('admin.academic-years.create') }}" class="btn btn-outline-primary-theme btn-sm mt-3">+ Tambah Sekarang</a>
                </div>
            </div>
        </div>
    @else
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-header-icon">📋</div>
                <h5 class="content-card-title">Daftar Tahun Ajaran</h5>
            </div>
            <div class="content-card-body" style="padding-top: 12px;">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Nama Tahun Ajaran</th>
                            <th>Rentang Waktu</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($years as $year)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary);">{{ $year->name }}</strong>
                                </td>
                                <td>
                                    @if($year->start_date && $year->end_date)
                                        <span class="small text-muted">{{ $year->start_date->translatedFormat('d M Y') }} - {{ $year->end_date->translatedFormat('d M Y') }}</span>
                                    @else
                                        <span class="small text-muted fst-italic">Belum diatur</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($year->is_active)
                                        <span class="status-badge status-badge--hadir">✓ Aktif</span>
                                    @else
                                        <span class="status-badge" style="background: rgba(0,0,0,0.05); color: var(--text-muted);">⊘ Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary-theme" href="{{ route('admin.academic-years.edit', $year) }}">✏️ Edit</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Hapus tahun ajaran ini?')) { document.getElementById('form-{{ $year->id }}').submit(); }" type="button">🗑️ Hapus</button>
                                    <form id="form-{{ $year->id }}" method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" style="display: none;">
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
    @endif
@endsection
