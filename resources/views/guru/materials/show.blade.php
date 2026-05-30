@extends('layouts.lms')

@section('title', $material->title)

@section('content')
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="h3 mb-0">Detail Materi</h1>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #25671E;">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3" style="color: #25671E;">{{ $material->title }}</h3>
            
            <div class="d-flex gap-3 flex-wrap mb-4 pb-4 border-bottom">
                <span class="badge bg-light text-dark border"><i class="fas fa-book me-1"></i> {{ $material->subject->name }}</span>
                <span class="badge bg-light text-dark border"><i class="fas fa-users me-1"></i> {{ $material->schoolClass->name }}</span>
                @if($material->meeting)
                    <span class="badge bg-light text-dark border"><i class="fas fa-calendar-day me-1"></i> Pertemuan {{ $material->meeting->number }}</span>
                @else
                    <span class="badge bg-light text-dark border"><i class="fas fa-star me-1"></i> Materi Mandiri</span>
                @endif
                <span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i> {{ $material->created_at->format('d M Y, H:i') }}</span>
            </div>

            @if($material->youtube_url)
                <div class="mb-5">
                    <h5 class="fw-bold mb-3" style="color: #25671E;"><i class="fab fa-youtube text-danger me-2"></i> Video Pembelajaran</h5>
                    @if($material->youtube_embed_url)
                        <div class="ratio ratio-16x9 bg-dark rounded overflow-hidden shadow-sm" style="max-width: 800px; margin: 0 auto;">
                            <iframe src="{{ $material->youtube_embed_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Link YouTube tidak valid: <a href="{{ $material->youtube_url }}" target="_blank">{{ $material->youtube_url }}</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($material->content)
                <div class="mb-5">
                    <h5 class="fw-bold mb-3" style="color: #25671E;"><i class="fas fa-align-left me-2"></i> Catatan Materi</h5>
                    <div class="p-4 bg-light rounded" style="font-size: 1.05rem; line-height: 1.6;">
                        {!! nl2br(e($material->content)) !!}
                    </div>
                </div>
            @endif

            @if($material->file_path)
                <div>
                    <h5 class="fw-bold mb-3" style="color: #25671E;"><i class="fas fa-file-pdf text-danger me-2"></i> Lampiran Dokumen</h5>
                    <div class="d-flex align-items-center p-3 bg-light rounded border" style="max-width: 400px;">
                        <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-bold">Dokumen Materi.pdf</div>
                            <small class="text-muted">Klik tombol di samping untuk membuka</small>
                        </div>
                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-success btn-sm">
                            Buka
                        </a>
                    </div>
                </div>
            @endif
            
            <div class="mt-5 pt-3 border-top">
                <a href="{{ route('guru.materials.edit', $material) }}" class="btn btn-warning text-dark fw-bold">
                    <i class="fas fa-edit me-1"></i> Edit Materi
                </a>
            </div>
        </div>
    </div>
@endsection
