@extends('layouts.lms')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="mb-5">
        <h1 class="h3 mb-2">📖 Pilih Mata Pelajaran</h1>
        <p class="text-muted">Pilih mata pelajaran untuk melihat daftar pertemuan dan materi</p>
    </div>

    @if($subjects->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-book-open fa-4x mb-4 text-light"></i>
                <h5>Belum ada mata pelajaran tersedia</h5>
                <p class="text-muted">Belum ada materi atau pertemuan yang dibagikan untuk kelas Anda.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($subjects as $subject)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 subject-card border-0 shadow-sm" 
                         style="cursor: pointer; border-radius: 15px; overflow: hidden; transition: all 0.3s ease;"
                         onclick="window.location='{{ route('siswa.subjects.show', $subject->id) }}'">
                        
                        <div class="card-body p-4 text-center">
                            <div class="icon-box mb-3 mx-auto shadow-sm d-flex align-items-center justify-content-center" 
                                 style="width: 70px; height: 70px; background-color: #f8f9fa; border-radius: 20px; border-bottom: 4px solid #48A111;">
                                <i class="fas fa-book fa-2x" style="color: #25671E;"></i>
                            </div>
                            
                            <h5 class="fw-bold mb-1" style="color: #25671E;">{{ $subject->name }}</h5>
                            <p class="text-muted small mb-3">Kode: {{ $subject->code ?? '-' }}</p>
                            
                            <div class="d-flex justify-content-center gap-2 mt-auto pt-3 border-top">
                                <span class="badge rounded-pill bg-light text-success border">
                                    Lihat Materi & Tugas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <style>
        .subject-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(37, 103, 30, 0.1) !important;
            background-color: #f0fdf4;
        }
        .subject-card:hover .icon-box {
            background-color: #48A111 !important;
        }
        .subject-card:hover .icon-box i {
            color: white !important;
        }
    </style>
@endsection
