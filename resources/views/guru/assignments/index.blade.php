@extends('layouts.lms')

@section('title', 'Tugas')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-2">📋 Kelola Tugas</h1>
            <p class="text-muted mb-0">Buat dan kelola tugas untuk siswa Anda</p>
        </div>
        <a class="btn btn-sm shadow-sm" style="background-color: #48A111; color: white; border: none;" href="{{ route('guru.assignments.create') }}">
            <i class="fas fa-plus me-2"></i> Buat Tugas Baru
        </a>
    </div>

    @if($assignments->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-tasks fa-4x mb-4 text-light"></i>
                <h5 class="mb-3">📭 Belum ada tugas</h5>
                <p class="text-muted mb-4">Mulai dengan membuat tugas baru untuk siswa Anda.</p>
                <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary" style="background-color: #48A111; border: none;">Buat Sekarang</a>
            </div>
        </div>
    @else
        <div class="row">
            @forelse($assignments as $a)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 assignment-card overflow-hidden" 
                         style="cursor: pointer; border: none; border-top: 5px solid #F2B50B; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"
                         onclick="window.location='{{ route('guru.assignments.show', $a) }}'">
                        
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-bold mb-1" style="color: #25671E;">{{ $a->title }}</h5>
                                    @if($a->due_at && \Carbon\Carbon::parse($a->due_at)->isFuture())
                                        <span class="badge" style="background-color: #48A111;">✓ Aktif</span>
                                    @else
                                        <span class="badge" style="background-color: #dc3545;">⊘ Ditutup</span>
                                    @endif
                                </div>
                                <small class="text-muted"><i class="fas fa-calendar-check me-1"></i> {{ $a->created_at->format('d M Y') }}</small>
                            </div>

                            <div class="mb-4">
                                <span class="d-inline-block small text-muted me-3"><i class="fas fa-door-open me-1 text-primary"></i> {{ $a->schoolClass?->name ?? 'Tanpa Kelas' }}</span>
                                <span class="d-inline-block small text-muted"><i class="fas fa-book me-1 text-success"></i> {{ $a->subject?->name ?? 'Tanpa Mapel' }}</span>
                            </div>

                            @if($a->due_at)
                                <div class="p-2 border rounded bg-light-subtle mb-3 small">
                                    <i class="fas fa-clock text-danger me-2"></i> Deadline: <strong class="text-danger">{{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</strong>
                                </div>
                            @endif

                            @if($a->meeting)
                                <div class="alert alert-light py-2 px-3 small border-0 mb-3 bg-light text-muted">
                                    <i class="fas fa-calendar-alt text-warning me-2"></i> Bagian dari: <strong>Pertemuan {{ $a->meeting->number }}</strong>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="small fw-bold text-muted">
                                    <i class="fas fa-user-check text-primary me-1"></i> {{ $a->submissions_count ?? $a->submissions->count() }} Terkumpul
                                </div>
                                @if($a->file_path)
                                    <span class="badge bg-light text-danger border" onclick="event.stopPropagation(); window.open('{{ asset('storage/' . $a->file_path) }}', '_blank')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 p-3 d-flex justify-content-end gap-2" onclick="event.stopPropagation();">
                            <a href="{{ route('guru.assignments.edit', $a) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('guru.assignments.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
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
            .assignment-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(242, 181, 11, 0.15) !important;
            }
        </style>

        <div class="mt-4">{{ $assignments->links() }}</div>
    @endif
@endsection
