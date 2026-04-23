@extends('layouts.lms')

@section('title', 'Kelola Tahun Ajaran')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">📅 Kelola Tahun Ajaran</h1>
            <p class="text-muted mb-0">Manage semua tahun ajaran yang tersedia</p>
        </div>
        <a class="btn btn-lg" style="background-color: #48A111; color: white; border: none;" href="{{ route('admin.academic-years.create') }}">+ Tambah Tahun Ajaran</a>
    </div>

    @if($years->isEmpty())
        <div class="alert alert-info border-top-4" style="border-top-color: #25671E;">
            <strong>ℹ️ Belum ada tahun ajaran</strong>
            <p class="mb-0 mt-2">Mulai dengan membuat tahun ajaran baru untuk sistem LMS.</p>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #F7F0F0;">
                    <tr>
                        <th style="border-left: 4px solid #25671E; color: #25671E;">📅 Nama Tahun Ajaran</th>
                        <th>⏳ Rentang Waktu</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">⚙️ Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($years as $year)
                        <tr>
                            <td>
                                <strong style="color: #25671E;">{{ $year->name }}</strong>
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
                                    <span class="badge" style="background-color: #48A111;">✓ Aktif</span>
                                @else
                                    <span class="badge" style="background-color: #ccc; color: #333;">⊘ Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.academic-years.edit', $year) }}">✏️ Edit</a>
                                <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Hapus tahun ajaran ini?')) { document.getElementById('form-{{ $year->id }}').submit(); }" type="button">🗑️ Hapus</button>
                                <form id="form-{{ $year->id }}" method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

