@extends('layouts.lms')

@section('title', 'Daftar Pertemuan')

@section('content')
    <div class="mb-5 reveal">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('guru.meetings.index') }}" style="color: var(--primary-light); text-decoration: none;">Ruang Kelas</a></li>
                <li class="breadcrumb-item active">{{ $currentClass->name }} - {{ $currentSubject->name }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                <a href="{{ route('guru.meetings.index') }}" class="btn btn-outline-secondary-theme btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <div>
                    <h1 class="h3 mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--primary);">🗓️ Daftar Pertemuan</h1>
                    <p class="text-muted mb-0">Kelola pertemuan kelas <strong>{{ $currentClass->name }}</strong> — <strong>{{ $currentSubject->name }}</strong></p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('forum.index', ['classSlug' => $currentClass->slug, 'subjectSlug' => $currentSubject->slug]) }}" class="btn btn-outline-primary-theme px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-comments"></i> Forum Diskusi
                </a>
                <a href="{{ route('guru.meetings.class-meetings.create', ['classSlug' => $currentClass->slug, 'subjectSlug' => $currentSubject->slug]) }}" class="btn btn-success px-4 py-2 border-0 shadow-sm d-flex align-items-center gap-2" style="background-color: var(--primary); border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Buat Pertemuan
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($meetings as $m)
            <div class="col-md-6 col-lg-4 mb-4 reveal">
                <div class="content-card meeting-card h-100 border-0 shadow-sm d-flex flex-column"
                     style="cursor: pointer; transition: all 0.3s cubic-bezier(0.22, 0.61, 0.36, 1); overflow: hidden; border-radius: 16px !important;"
                     onclick="window.location='{{ route('guru.meetings.show', $m) }}'">

                    {{-- Color accent strip --}}
                    <div style="height: 4px; background: {{ $m->is_visible ? 'linear-gradient(90deg, var(--primary), var(--accent))' : 'linear-gradient(90deg, #adb5bd, #ced4da)' }};"></div>

                    <div class="p-4 flex-grow-1 d-flex flex-column">
                        {{-- Row 1: Badge + Status --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                Pertemuan {{ $m->number }}
                            </span>
                            @if($m->is_visible)
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                                    <i class="fas fa-eye me-1"></i>Terlihat
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                                    <i class="fas fa-eye-slash me-1"></i>Draf
                                </span>
                            @endif
                        </div>

                        {{-- Row 2: Title --}}
                        <h6 class="fw-bold text-dark mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.05rem; line-height: 1.45;">{{ $m->title }}</h6>

                        {{-- Row 3: Date --}}
                        <div class="text-muted small mb-auto d-flex align-items-center gap-2 flex-wrap">
                            <span><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($m->date)->format('d M Y') }}</span>
                            @if($block = $m->schedule_block)
                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem; font-weight: 600;">
                                    <i class="far fa-clock me-1 text-success"></i>{{ $block->jp_count }} JP ({{ \Carbon\Carbon::parse($block->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($block->end_time)->format('H:i') }})
                                </span>
                            @endif
                        </div>

                        {{-- Row 4: Stats chips --}}
                        <div class="d-flex flex-wrap gap-2 mt-3 pt-3" style="border-top: 1px solid rgba(0,0,0,0.04);">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">
                                <i class="fas fa-book me-1"></i>{{ $m->materials->count() }} Materi
                            </span>
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">
                                <i class="fas fa-tasks me-1"></i>{{ $m->assignments->count() }} Tugas
                            </span>
                            @if($m->video_link)
                                @if($m->video_link_status === 'finished')
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">
                                        <i class="fas fa-video-slash me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">
                                        <i class="fas fa-video me-1"></i>Virtual
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Bottom action bar --}}
                    <div class="px-4 pb-3 pt-0 d-flex align-items-center justify-content-between" onclick="event.stopPropagation();">
                        {{-- Primary CTA --}}
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('guru.meetings.show', $m) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.78rem; background: rgba(27,94,32,0.06); color: var(--primary);">
                                Detail Sesi <i class="fas fa-arrow-right ms-1" style="font-size: 0.65rem;"></i>
                            </a>
                            @if($m->video_link && $m->video_link_status !== 'finished')
                                <a href="{{ $m->video_link }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold text-white" style="font-size: 0.78rem; background-color: var(--primary); border: none;">
                                    <i class="fas fa-video me-1"></i>Buka
                                </a>
                            @endif
                        </div>

                        {{-- Icon tools --}}
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-sm btn-icon-tool" title="{{ $m->video_link ? 'Kelola Link Virtual' : 'Tambah Link Virtual' }}" data-bs-toggle="modal" data-bs-target="#videoLinkModal{{ $m->id }}">
                                <i class="fas fa-video"></i>
                            </button>
                            <form action="{{ route('guru.meetings.toggleVisibility', $m) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-icon-tool" title="{{ $m->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                    <i class="fas {{ $m->is_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('guru.meetings.edit', $m) }}" class="btn btn-sm btn-icon-tool" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(session()->has('impersonate_original_id'))
                                <form action="{{ route('guru.meetings.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus pertemuan ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon-tool btn-icon-tool--danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5 reveal">
                <div class="empty-state">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-calendar-day fa-4x text-success" style="opacity: 0.6;"></i>
                    </div>
                    <h4 class="empty-state-text" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">📭 Belum Ada Pertemuan</h4>
                    <p class="text-muted mb-4 max-w-md mx-auto">Belum ada pertemuan untuk kelas dan mata pelajaran ini.</p>
                    <a href="{{ route('guru.meetings.class-meetings.create', ['classSlug' => $currentClass->slug, 'subjectSlug' => $currentSubject->slug]) }}" class="btn btn-success px-4 py-2 border-0" style="background-color: var(--primary); border-radius: 12px; font-weight: 600;">
                        + Buat Pertemuan Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <style>
        .meeting-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(37, 103, 30, 0.10) !important;
        }
        .btn-icon-tool {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.08);
            background: transparent;
            color: #6c757d;
            font-size: 0.72rem;
            transition: all 0.2s ease;
        }
        .btn-icon-tool:hover {
            background: rgba(27, 94, 32, 0.06);
            border-color: rgba(27, 94, 32, 0.15);
            color: var(--primary);
        }
        .btn-icon-tool--danger:hover {
            background: rgba(220, 53, 69, 0.06);
            border-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
    </style>

@push('modals')
    <!-- Modals Kelola Link Virtual -->
    @foreach($meetings as $m)
        <div class="modal fade" id="videoLinkModal{{ $m->id }}" tabindex="-1" aria-labelledby="videoLinkModalLabel{{ $m->id }}" aria-hidden="true" onclick="event.stopPropagation();">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <form action="{{ route('guru.meetings.updateVideoLink', $m) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold" id="videoLinkModalLabel{{ $m->id }}" style="font-family: 'Plus Jakarta Sans', sans-serif; color: var(--primary);">
                                🎥 Kelola Link Virtual (Pertemuan {{ $m->number }})
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 py-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">Link Google Meet / Zoom</label>
                                <input type="url" name="video_link" class="form-control" value="{{ $m->video_link }}" placeholder="https://meet.google.com/... atau https://zoom.us/..." style="border-radius: 10px;">
                                <div class="form-text text-muted small mt-2">Masukkan URL video conference lengkap. Kosongkan untuk menghapus link.</div>
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
    @endforeach
@endpush
@endsection
