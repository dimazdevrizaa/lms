@extends('layouts.lms')

@section('title', 'Detail Pertemuan')

@section('content')
    <!-- Header -->
    <div class="mb-5 reveal">
        @php
            $backUrl = auth()->user()->role === 'admin'
                ? route('admin.attendances.showSubject', ['class' => $meeting->class_id, 'subject' => $meeting->subject_id])
                : route('guru.meetings.class-meetings', ['classSlug' => $meeting->schoolClass->slug, 'subjectSlug' => $meeting->subject->slug]);
        @endphp
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary-theme btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <div>
                    <nav aria-label="breadcrumb" class="mb-1">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('guru.meetings.index') }}" style="color: var(--primary-light); text-decoration: none;">Ruang Kelas</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('guru.meetings.class-meetings', ['classSlug' => $meeting->schoolClass->slug, 'subjectSlug' => $meeting->subject->slug]) }}" style="color: var(--primary-light); text-decoration: none;">{{ $meeting->schoolClass->name }} - {{ $meeting->subject->name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary);">🗓️ Pertemuan Ke-{{ $meeting->number }}: {{ $meeting->title }}</h1>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('guru.meetings.toggleVisibility', $meeting) }}" method="POST" class="d-inline">
                    @csrf
                    @if($meeting->is_visible)
                        <button type="submit" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 10px; font-weight: 600;" title="Sembunyikan dari Siswa">
                            <i class="fas fa-eye-slash me-1"></i> Sembunyikan dari Siswa
                        </button>
                    @else
                        <button type="submit" class="btn btn-success px-3 py-2" style="border-radius: 10px; font-weight: 600; background-color: var(--primary); border: none;" title="Tampilkan ke Siswa">
                            <i class="fas fa-eye me-1"></i> Tampilkan ke Siswa
                        </button>
                    @endif
                </form>
                <a href="{{ route('guru.meetings.edit', $meeting) }}" class="btn btn-outline-primary px-3 py-2" style="border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-edit me-1"></i> Edit Sesi
                </a>
                @if(session()->has('impersonate_original_id'))
                    <form action="{{ route('guru.meetings.destroy', $meeting) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertemuan ini? Semua materi dan tugas di dalamnya akan ikut terhapus.')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-3 py-2" style="border-radius: 10px; font-weight: 600;">
                            <i class="fas fa-trash me-1"></i> Hapus Sesi
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="d-flex gap-3 text-muted flex-wrap align-items-center">
            <span class="d-flex align-items-center gap-1"><i class="fas fa-door-open text-success"></i> {{ $meeting->schoolClass->name }}</span>
            <span class="d-flex align-items-center gap-1"><i class="fas fa-book text-success"></i> {{ $meeting->subject->name }}</span>
            <span class="d-flex align-items-center gap-1"><i class="fas fa-calendar text-success"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</span>
            @if($block = $meeting->schedule_block)
                <span class="d-flex align-items-center gap-1"><i class="fas fa-clock text-success"></i> {{ $block->jp_count }} JP ({{ \Carbon\Carbon::parse($block->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($block->end_time)->format('H:i') }})</span>
            @endif
            @if($meeting->is_visible)
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;"><i class="fas fa-eye me-1"></i> Terlihat oleh Siswa</span>
            @else
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;"><i class="fas fa-eye-slash me-1"></i> Disembunyikan (Draf)</span>
            @endif
        </div>
    </div>



    @if($meeting->description)
        <div class="content-card mb-4 reveal" style="border-left: 4px solid var(--accent);">
            <div class="content-card-body p-4">
                <h6 class="fw-bold mb-2 text-muted" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi / Tujuan Pembelajaran:</h6>
                <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $meeting->description }}</p>
            </div>
        </div>
    @endif

    <!-- Absensi Section -->
    <div class="content-card mb-5 reveal">
        <div class="content-card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="content-card-header-icon" style="background-color: rgba(27, 94, 32, 0.08); color: var(--primary);">
                    <i class="fas fa-user-check"></i>
                </div>
                <h5 class="content-card-title mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Presensi Siswa</h5>
            </div>
            @if($meeting->attendance)
                <div class="d-flex align-items-center gap-2">
                    @if($meeting->attendance->formatted_submitted_time)
                        <span class="badge bg-light text-primary border px-3 py-1.5 small font-monospace">
                            <i class="far fa-clock me-1"></i>Diisi jam <strong>{{ $meeting->attendance->formatted_submitted_time }} WIB</strong>
                        </span>
                    @endif
                    <a href="{{ route('guru.attendances.show', $meeting->attendance) }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;">
                        <i class="fas fa-eye me-1"></i> Detail Presensi
                    </a>
                </div>
            @else
                <a href="{{ route('guru.attendances.create', ['meeting_id' => $meeting->id]) }}" class="btn btn-sm btn-primary px-3" style="background-color: var(--primary); border: none; border-radius: 8px;">
                    <i class="fas fa-plus me-1"></i> Isi Presensi
                </a>
            @endif
        </div>
        <div class="content-card-body p-4">
            @if($meeting->attendance)
                <div class="stats-grid mb-4">
                    <!-- Hadir -->
                    <div class="stat-card" style="border-left: 4px solid var(--primary);">
                        <div class="stat-icon-circle stat-icon-circle--green">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">HADIR</div>
                            <div class="stat-value stat-value--green">{{ $meeting->attendance->details->where('status', 'hadir')->count() }}</div>
                        </div>
                    </div>
                    <!-- Izin -->
                    <div class="stat-card" style="border-left: 4px solid var(--accent);">
                        <div class="stat-icon-circle" style="background: rgba(249, 168, 37, 0.08); color: var(--accent);">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div class="stat-label">IZIN</div>
                            <div class="stat-value" style="color: var(--accent);">{{ $meeting->attendance->details->where('status', 'izin')->count() }}</div>
                        </div>
                    </div>
                    <!-- Sakit -->
                    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
                        <div class="stat-icon-circle" style="background: rgba(67, 160, 71, 0.08); color: var(--secondary);">
                            <i class="fas fa-briefcase-medical"></i>
                        </div>
                        <div>
                            <div class="stat-label">SAKIT</div>
                            <div class="stat-value text-info">{{ $meeting->attendance->details->where('status', 'sakit')->count() }}</div>
                        </div>
                    </div>
                    <!-- Alpa -->
                    <div class="stat-card" style="border-left: 4px solid #dc3545;">
                        <div class="stat-icon-circle" style="background: rgba(220, 53, 69, 0.08); color: #dc3545;">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div>
                            <div class="stat-label">ALPA</div>
                            <div class="stat-value text-danger">{{ $meeting->attendance->details->where('status', 'alpa')->count() }}</div>
                        </div>
                    </div>
                </div>

                @php
                    $absentees = $meeting->attendance->details->whereIn('status', ['izin', 'sakit', 'alpa']);
                @endphp
                
                @if($absentees->isNotEmpty())
                    <div class="mt-4 pt-3 border-top" style="border-color: rgba(37, 103, 30, 0.04) !important;">
                        <h6 class="text-muted fw-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;"><i class="fas fa-exclamation-circle text-warning me-1"></i> Daftar Siswa Tidak Hadir:</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="font-weight: 600; font-size: 0.8rem;">Nama Siswa</th>
                                        <th style="font-weight: 600; font-size: 0.8rem; width: 150px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absentees as $absentee)
                                        <tr>
                                            <td><strong class="text-dark">{{ $absentee->student->user->name }}</strong></td>
                                            <td>
                                                <span class="status-badge status-badge--{{ $absentee->status }}">
                                                    {{ strtoupper($absentee->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mt-3 text-center text-success small fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Semua siswa hadir hari ini.
                    </div>
                @endif
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-user-slash fa-2x mb-3 text-light"></i>
                    <p class="mb-0">Daftar presensi belum diisi untuk pertemuan ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Online Meeting Section -->
    <div class="content-card mb-5 reveal">
        <div class="content-card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="content-card-header-icon" style="background-color: rgba(13, 202, 240, 0.08); color: var(--info);">
                    <i class="fas fa-video"></i>
                </div>
                <h5 class="content-card-title mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Kelas Virtual / Online Meeting</h5>
            </div>
            @if($meeting->video_link)
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#editVideoLinkModal">
                        <i class="fas fa-edit me-1"></i> Edit Link
                    </button>
                    <form action="{{ route('guru.meetings.updateVideoLink', $meeting) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus link video conference?')">
                        @csrf
                        <input type="hidden" name="video_link" value="">
                        <button type="submit" class="btn btn-sm btn-outline-danger px-3" style="border-radius: 8px;">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            @else
                <button type="button" class="btn btn-sm btn-primary px-3" style="background-color: var(--primary); border: none; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#editVideoLinkModal">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
            @endif
        </div>
        <div class="content-card-body p-4">
            @if($meeting->video_link)
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                            Link Pertemuan Virtual 
                            @if($meeting->video_link_status === 'finished')
                                <span class="badge bg-secondary-subtle text-secondary ms-2" style="font-size: 0.75rem;">Selesai</span>
                            @else
                                <span class="badge bg-success-subtle text-success ms-2" style="font-size: 0.75rem;">Aktif</span>
                            @endif
                        </h6>
                        <a href="{{ $meeting->video_link }}" target="_blank" class="small text-primary d-inline-flex align-items-center gap-1" style="text-decoration: none; word-break: break-all;">
                            <i class="fas fa-external-link-alt"></i> {{ $meeting->video_link }}
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($meeting->video_link_status === 'finished')
                            <form action="{{ route('guru.meetings.updateVideoLink', $meeting) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="video_link" value="{{ $meeting->video_link }}">
                                <input type="hidden" name="video_link_status" value="active">
                                <button type="submit" class="btn btn-outline-success px-4 py-2" style="border-radius: 10px; font-weight: 600;">
                                    <i class="fas fa-undo me-2"></i> Buka Kembali
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-outline-warning px-4 py-2" data-bs-toggle="modal" data-bs-target="#confirmFinishModal" style="border-radius: 10px; font-weight: 600; border-color: #ffc107; color: #ffc107;" onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ffc107';">
                                <i class="fas fa-check-circle me-2"></i> Selesai
                            </button>
                            <a href="{{ $meeting->video_link }}" target="_blank" class="btn btn-success px-4 py-2 border-0" style="background-color: var(--primary); border-radius: 10px; font-weight: 600;">
                                <i class="fas fa-video me-2"></i> Buka Kelas Virtual
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-video-slash fa-2x mb-3 text-light"></i>
                    <p class="mb-0">Belum ada link video conference (Google Meet/Zoom) untuk pertemuan ini.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Materi Section -->
        <div class="col-lg-6 mb-4 reveal" id="materi-section">
            <div class="content-card h-100">
                <div class="content-card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="content-card-header-icon" style="background-color: rgba(27, 94, 32, 0.08); color: var(--primary);">
                            <i class="fas fa-book"></i>
                        </div>
                        <h5 class="content-card-title mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Materi Pembelajaran</h5>
                    </div>
                    <a href="{{ route('guru.materials.create', ['meeting_id' => $meeting->id, 'class_id' => $meeting->class_id, 'subject_id' => $meeting->subject_id]) }}" class="btn btn-sm btn-outline-success px-3" style="border-radius: 8px;">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                <div class="content-card-body p-4">
                    @forelse($meeting->materials as $material)
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: rgba(37, 103, 30, 0.04) !important;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark fw-bold" style="font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $material->title }}</h6>
                                @if($material->file_path)
                                    <a href="{{ route('materials.view-file', $material) }}" target="_blank" class="small text-danger d-inline-flex align-items-center gap-1" style="text-decoration: none; font-weight: 600;">
                                        <i class="fas fa-file-pdf"></i> Lihat PDF
                                    </a>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('guru.materials.edit', $material) }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill d-inline-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem; font-weight: 600;" title="Edit Materi">
                                    <i class="fas fa-edit small"></i> Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 text-light animate-bounce"></i>
                            <p class="mb-0">Belum ada materi untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tugas Section -->
        <div class="col-lg-6 mb-4 reveal" id="tugas-section">
            <div class="content-card h-100">
                <div class="content-card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="content-card-header-icon" style="background-color: rgba(27, 94, 32, 0.08); color: var(--primary);">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h5 class="content-card-title mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Tugas Siswa</h5>
                    </div>
                    <a href="{{ route('guru.assignments.create', ['meeting_id' => $meeting->id, 'class_id' => $meeting->class_id, 'subject_id' => $meeting->subject_id]) }}" class="btn btn-sm btn-outline-success px-3" style="border-radius: 8px;">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                <div class="content-card-body p-4">
                    @forelse($meeting->assignments as $assignment)
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: rgba(37, 103, 30, 0.04) !important;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark fw-bold" style="font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $assignment->title }}</h6>
                                <small class="text-danger d-flex align-items-center gap-1" style="font-weight: 500;">
                                    <i class="fas fa-clock"></i> Batas: {{ \Carbon\Carbon::parse($assignment->due_at)->format('d M Y, H:i') }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('guru.assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.8rem; font-weight: 600;" title="Detail Tugas">
                                    Detail
                                </a>
                                <a href="{{ route('guru.assignments.edit', $assignment) }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill d-inline-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem; font-weight: 600;" title="Edit Tugas">
                                    <i class="fas fa-edit small"></i> Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                            <p class="mb-0">Belum ada tugas untuk pertemuan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Discussion Forum --}}
    @include('partials.meeting-discussion', ['meeting' => $meeting, 'discussionPosts' => $discussionPosts])

@push('modals')
    <!-- Modal Kelola Link Virtual -->
    <div class="modal fade" id="editVideoLinkModal" tabindex="-1" aria-labelledby="editVideoLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <form action="{{ route('guru.meetings.updateVideoLink', $meeting) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold" id="editVideoLinkModalLabel" style="font-family: 'Plus Jakarta Sans', sans-serif; color: var(--primary);">
                            🎥 Kelola Link Virtual
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-2">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Link Google Meet / Zoom</label>
                            <input type="url" name="video_link" class="form-control" value="{{ $meeting->video_link }}" placeholder="https://meet.google.com/... atau https://zoom.us/..." style="border-radius: 10px;" required>
                            <div class="form-text text-muted small mt-2">Masukkan URL video conference lengkap (Google Meet / Zoom).</div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pb-4 px-4">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: var(--primary); border: none; border-radius: 10px; font-weight: 600;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Selesai -->
    <div class="modal fade" id="confirmFinishModal" tabindex="-1" aria-labelledby="confirmFinishModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <form action="{{ route('guru.meetings.updateVideoLink', $meeting) }}" method="POST">
                    @csrf
                    <input type="hidden" name="video_link" value="{{ $meeting->video_link }}">
                    <input type="hidden" name="video_link_status" value="finished">
                    <div class="modal-header border-bottom-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold" id="confirmFinishModalLabel" style="font-family: 'Plus Jakarta Sans', sans-serif; color: #ffc107;">
                            ⚠️ Konfirmasi Selesai
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-2">
                        <p class="text-dark mb-0">Apakah Anda yakin ingin menandai kelas virtual ini telah selesai? Link virtual akan dinonaktifkan untuk siswa.</p>
                    </div>
                    <div class="modal-footer border-top-0 pb-4 px-4">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-warning text-white px-4 py-2" style="background-color: #ffc107; border: none; border-radius: 10px; font-weight: 600;">Ya, Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@endsection
