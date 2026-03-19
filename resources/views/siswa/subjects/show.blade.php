@extends('layouts.lms')

@section('title', 'Daftar Pertemuan')

@section('content')
    <div class="mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('siswa.subjects.index') }}" style="color: #48A111;">Mata Pelajaran</a></li>
                <li class="breadcrumb-item active">{{ $subject->name }}</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">🗓️ Daftar Pertemuan: {{ $subject->name }}</h1>
        <p class="text-muted">Pilih pertemuan untuk melihat materi dan tugas hari ini</p>
    </div>

    <div class="row">
        @forelse($meetings as $meeting)
            <div class="col-md-6 mb-4">
                <div class="card h-100 meeting-card border-0 shadow-sm" 
                     style="cursor: pointer; border-radius: 12px; transition: all 0.3s ease; border-left: 6px solid #F2B50B;"
                     onclick="window.location='{{ route('siswa.meetings.show', $meeting->id) }}'">
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge mb-2" style="background-color: #48A111;">Pertemuan ke-{{ $meeting->number }}</span>
                                <h5 class="fw-bold mb-1" style="color: #25671E;">{{ $meeting->title }}</h5>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-chalkboard-user text-muted me-2"></i>
                            <span class="small text-muted">Guru: {{ $meeting->teacher->user->name }}</span>
                        </div>

                        <div class="d-flex gap-3 pt-3 border-top">
                            <span class="small text-muted"><i class="fas fa-file-pdf me-1 text-danger"></i> Materi</span>
                            <span class="small text-muted"><i class="fas fa-tasks me-1 text-warning"></i> Tugas</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-day fa-4x mb-3 text-light"></i>
                <h5 class="text-muted">Belum ada pertemuan untuk mata pelajaran ini.</h5>
            </div>
        @endforelse
    </div>

    @if(!$standaloneMaterials->isEmpty())
        <div class="mt-5 mb-4">
            <h5 class="fw-bold" style="color: #25671E;"><i class="fas fa-folder-open me-2"></i> Materi Mandiri (Tanpa Pertemuan)</h5>
            <p class="text-muted small">Materi pendukung lainnya untuk mata pelajaran ini</p>
        </div>
        <div class="row">
            @foreach($standaloneMaterials as $m)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px; border-top: 4px solid #48A111;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">{{ $m->title }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($m->content, 80) }}</p>
                            @if($m->file_path)
                                <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <style>
        .meeting-card:hover {
            transform: translateX(8px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            background-color: #fffdf5;
            border-left-color: #25671E !important;
        }
    </style>
@endsection
