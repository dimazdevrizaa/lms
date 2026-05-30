@extends('layouts.lms')

@section('title', $material->title)

@section('content')
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            @if($material->meeting)
                <a href="{{ route('siswa.meetings.show', $material->meeting) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            @else
                <a href="{{ route('siswa.subjects.show', $material->subject_id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            @endif
            <h1 class="h3 mb-0">Materi Pembelajaran</h1>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #48A111;">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3" style="color: #25671E;">{{ $material->title }}</h3>
            
            <div class="d-flex gap-3 flex-wrap mb-4 pb-4 border-bottom">
                <span class="badge bg-light text-dark border"><i class="fas fa-book me-1"></i> {{ $material->subject->name }}</span>
                <span class="badge bg-light text-dark border"><i class="fas fa-chalkboard-user me-1"></i> {{ $material->teacher->user->name }}</span>
                @if($material->meeting)
                    <span class="badge bg-light text-dark border"><i class="fas fa-calendar-day me-1"></i> Pertemuan {{ $material->meeting->number }}</span>
                @endif
                <span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i> {{ $material->created_at->format('d M Y, H:i') }}</span>
            </div>

            @if($material->youtube_url)
                <div class="mb-5">
                    @if($material->youtube_embed_url)
                        <div class="ratio ratio-16x9 bg-dark rounded overflow-hidden shadow-sm" style="max-width: 800px; margin: 0 auto;">
                            <iframe src="{{ $material->youtube_embed_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-video me-2"></i> Terdapat lampiran video, namun format link tidak didukung. <a href="{{ $material->youtube_url }}" target="_blank" class="alert-link">Buka di YouTube</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($material->content)
                <div class="mb-5">
                    <div class="p-4 bg-light rounded text-dark" style="font-size: 1.05rem; line-height: 1.6;">
                        {!! nl2br(e($material->content)) !!}
                    </div>
                </div>
            @endif

            @if($material->file_path)
                <div class="mt-4 pt-4 border-top">
                    <h5 class="fw-bold mb-3" style="color: #25671E;"><i class="fas fa-file-pdf text-danger me-2"></i> Lampiran Dokumen</h5>
                    <div class="d-flex align-items-center p-3 bg-light rounded border item-hover-card" 
                         style="max-width: 400px; cursor: pointer; transition: 0.2s;"
                         onclick="window.open('{{ asset('storage/' . $material->file_path) }}', '_blank')">
                        <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-bold">Dokumen Materi.pdf</div>
                            <small class="text-muted">Klik untuk membuka</small>
                        </div>
                        <span class="btn btn-success btn-sm">Buka</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <style>
        .item-hover-card:hover {
            transform: translateY(-3px);
            background-color: #e9ecef !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
@endsection
