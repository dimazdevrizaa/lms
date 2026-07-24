@extends('layouts.lms')

@section('title', 'Kelola Tugas - Pertemuan #' . $meeting->number)

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.attendances.index') }}">Presensi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.attendances.showClass', $class->id) }}">{{ $class->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.attendances.showSubject', ['class' => $class->id, 'subject' => $subject->id]) }}">{{ $subject->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Tugas</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.attendances.showSubject', ['class' => $class->id, 'subject' => $subject->id]) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pertemuan
                </a>
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">📝 Kelola Tugas Siswa</h1>
                    <p class="text-muted mb-0 small">
                        Pertemuan #{{ $meeting->number }}: <strong>{{ $meeting->title }}</strong> · Kelas: <strong>{{ $class->name }}</strong> · Guru: <strong>{{ $meeting->teacher->user->name ?? '-' }}</strong>
                    </p>
                </div>
            </div>

            <a href="{{ route('guru.assignments.create', ['meeting_id' => $meeting->id, 'class_id' => $class->id, 'subject_id' => $subject->id]) }}" class="btn btn-primary rounded-3 px-4 fw-semibold">
                <i class="fas fa-plus me-1"></i> Buat Tugas Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-tasks text-warning me-2"></i>Daftar Tugas untuk Pertemuan Ini ({{ $assignments->count() }})</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">#</th>
                        <th class="py-3">Judul Tugas</th>
                        <th class="py-3">Tipe</th>
                        <th class="py-3">Batas Waktu</th>
                        <th class="py-3">Pengumpulan</th>
                        <th class="text-end pe-4 py-3" style="width: 240px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $index => $assignment)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $assignment->title }}</div>
                                @if($assignment->description)
                                    <small class="text-muted text-truncate d-block" style="max-width: 350px;">{{ strip_tags($assignment->description) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($assignment->type === 'online')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-medium">
                                        <i class="fas fa-laptop me-1"></i> Online / Quiz
                                    </span>
                                @elseif($assignment->type === 'pdf')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-medium">
                                        <i class="fas fa-file-pdf me-1"></i> Upload Berkas
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-medium">
                                        <i class="fas fa-external-link-alt me-1"></i> Link Eksternal
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                @if($assignment->due_at)
                                    <i class="far fa-clock text-danger me-1"></i> {{ \Carbon\Carbon::parse($assignment->due_at)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Tanpa Batas</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1.5 fw-semibold">
                                    <i class="fas fa-users text-primary me-1"></i> {{ $assignment->submissions->count() }} Submissions
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('guru.assignments.show', $assignment->id) }}" class="btn btn-sm btn-outline-info rounded-3" title="Detail & Penilaian">
                                        <i class="fas fa-eye me-1"></i> Detail / Nilai
                                    </a>
                                    <a href="{{ route('guru.assignments.edit', $assignment->id) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit Tugas">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('guru.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus Tugas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-tasks fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                <p class="mb-2 fw-semibold">Belum ada tugas untuk pertemuan ini.</p>
                                <a href="{{ route('guru.assignments.create', ['meeting_id' => $meeting->id, 'class_id' => $class->id, 'subject_id' => $subject->id]) }}" class="btn btn-sm btn-primary rounded-3 px-3">
                                    <i class="fas fa-plus me-1"></i> Buat Tugas Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
