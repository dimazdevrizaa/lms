@extends('layouts.lms')

@section('title', 'Daftar Pertemuan')

@section('content')
    <div class="mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('guru.meetings.index') }}" style="color: #48A111;">Ruang Kelas</a></li>
                <li class="breadcrumb-item active">{{ $currentClass->name }} - {{ $currentSubject->name }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2">🗓️ Daftar Pertemuan: {{ $currentClass->name }}</h1>
                <p class="text-muted mb-0">Kelola semua pertemuan untuk mata pelajaran **{{ $currentSubject->name }}**</p>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('guru.meetings.index') }}">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($meetings as $m)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 meeting-card border-0 shadow-sm" 
                     style="cursor: pointer; border-radius: 15px; transition: all 0.3s ease; border-left: 6px solid #F2B50B;"
                     onclick="window.location='{{ route('guru.meetings.show', $m) }}'">
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge mb-2" style="background-color: #48A111;">Pertemuan ke-{{ $m->number }}</span>
                                <h5 class="fw-bold mb-1" style="color: #25671E;">{{ $m->title }}</h5>
                                <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($m->date)->format('d M Y') }}</small>
                            </div>
                        </div>

                        <div class="d-flex gap-3 pt-3 border-top mt-3">
                            <div class="small text-muted">
                                <i class="fas fa-book me-1 text-primary"></i> {{ $m->materials->count() }} Materi
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-tasks me-1 text-warning"></i> {{ $m->assignments->count() }} Tugas
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 p-3 d-flex justify-content-end gap-2" onclick="event.stopPropagation();">
                        <a href="{{ route('guru.meetings.edit', $m) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('guru.meetings.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus pertemuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-day fa-4x mb-3 text-light"></i>
                <h5 class="text-muted">Belum ada pertemuan untuk kelas dan mata pelajaran ini.</h5>
                <a href="{{ route('guru.meetings.create', ['class_id' => $currentClass->id, 'subject_id' => $currentSubject->id]) }}" class="btn btn-primary mt-3" style="background-color: #48A111; border: none;">+ Buat Pertemuan</a>
            </div>
        @endforelse
    </div>

    <style>
        .meeting-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            border-left-color: #25671E !important;
        }
    </style>
@endsection
