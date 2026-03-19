@extends('layouts.lms')

@section('title', 'Materi')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">📚 Kelola Materi Pembelajaran</h1>
            <p class="text-muted mb-0">Upload dan kelola materi untuk siswa Anda</p>
        </div>
        <a class="btn btn-sm" style="background-color: #48A111; color: white; border: none; box-shadow: 0 2px 8px rgba(72, 161, 17, 0.2);" href="{{ route('guru.materials.create') }}">
            <i class="fas fa-plus me-2"></i> Upload Materi Baru
        </a>
    </div>

    @if($materials->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-book-open fa-4x mb-4 text-light"></i>
                <h5 class="mb-3">📭 Belum ada materi</h5>
                <p class="text-muted mb-4">Mulai dengan upload materi pembelajaran untuk siswa Anda.</p>
                <a href="{{ route('guru.materials.create') }}" class="btn btn-primary" style="background-color: #48A111; border: none;">Upload Sekarang</a>
            </div>
        </div>
    @else
        <div class="row">
            @forelse($materials as $m)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 material-card overflow-hidden" 
                         style="cursor: pointer; border: none; border-left: 5px solid #25671E; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"
                         onclick="window.location='{{ route('guru.materials.edit', $m) }}'">
                        
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-bold mb-1" style="color: #25671E;">{{ $m->title }}</h5>
                                    <span class="badge" style="background-color: #48A111; padding: 0.5em 1em;">📖 Materi</span>
                                </div>
                                <small class="text-muted"><i class="fas fa-calendar me-1"></i> {{ $m->created_at->format('d M Y') }}</small>
                            </div>

                            <div class="mb-3">
                                <span class="d-inline-block small text-muted me-3"><i class="fas fa-door-open me-1 text-primary"></i> {{ $m->schoolClass?->name ?? 'Tanpa Kelas' }}</span>
                                <span class="d-inline-block small text-muted"><i class="fas fa-book me-1 text-success"></i> {{ $m->subject?->name ?? 'Tanpa Mapel' }}</span>
                            </div>

                            @if($m->meeting)
                                <div class="alert alert-light py-2 px-3 small border-0 bg-light-subtle mb-3">
                                    <i class="fas fa-calendar-alt text-warning me-2"></i> Terhubung ke: <strong style="color: #25671E;">Pertemuan {{ $m->meeting->number }}</strong>
                                </div>
                            @endif

                            @if($m->file_path)
                                <div class="mt-3 p-2 border rounded bg-white text-center">
                                    <i class="fas fa-file-pdf text-danger fa-2x mb-1"></i>
                                    <div class="small fw-bold">PDF Terlampir</div>
                                    <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="small text-decoration-none" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top-0 p-3 d-flex justify-content-end gap-2" onclick="event.stopPropagation();">
                            <a href="{{ route('guru.materials.edit', $m) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('guru.materials.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
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
            @endforelse
        </div>

        <style>
            .material-card:hover {
                transform: translateX(10px);
                box-shadow: 0 10px 25px rgba(37, 103, 30, 0.1) !important;
            }
        </style>

        <div class="mt-4">{{ $materials->links() }}</div>
    @endif
@endsection
