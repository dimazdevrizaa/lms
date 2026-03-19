@extends('layouts.lms')

@section('title', 'Pembelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-5">
        <h1 class="h3 mb-2">📚 Ruang Belajar</h1>
        <p class="text-muted mb-0">Akses materi dan tugas yang dikelompokkan per pertemuan</p>
    </div>

    @if($meetings->isEmpty() && $standaloneMaterials->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="fas fa-book-open fa-4x mb-4" style="color: #e2e8f0;"></i>
                <h5 class="mb-3">📭 Belum ada konten pembelajaran</h5>
                <p class="text-muted mb-0">Guru belum membagikan pertemuan atau materi untuk kelas Anda.</p>
            </div>
        </div>
    @else
        <!-- Meetings List -->
        @foreach($meetings as $meeting)
            <div class="card mb-4 overflow-hidden" style="border: none; border-left: 5px solid #25671E; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge mb-2" style="background-color: #48A111;">Pertemuan ke-{{ $meeting->number }}</span>
                            <h4 class="mb-1" style="color: #25671E;">{{ $meeting->title }}</h4>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fas fa-book me-1"></i> {{ $meeting->subject->name }}</span>
                                <span class="me-3"><i class="fas fa-user-tie me-1"></i> {{ $meeting->teacher->user->name }}</span>
                                <span><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light-subtle">
                    @if($meeting->description)
                        <p class="text-muted small border-bottom pb-3 mb-3 italic">{{ $meeting->description }}</p>
                    @endif

                    <div class="row">
                        <!-- Materials for this meeting -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-uppercase small fw-bold mb-3" style="color: #25671E; letter-spacing: 1px;">📚 Materi</h6>
                            @forelse($meeting->materials as $m)
                                <div class="d-flex align-items-center p-2 mb-2 bg-white rounded border item-hover-card" 
                                     style="cursor: pointer; transition: all 0.2s;"
                                     onclick="window.open('{{ asset('storage/' . $m->file_path) }}', '_blank')">
                                    <i class="fas fa-file-pdf text-danger me-3 fa-lg"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">{{ $m->title }}</div>
                                    </div>
                                    @if($m->file_path)
                                        <span class="btn btn-sm btn-outline-success">Buka</span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-muted small italic p-2">Tidak ada materi khusus untuk pertemuan ini.</div>
                            @endforelse
                        </div>

                        <!-- Assignments for this meeting -->
                        <div class="col-md-6">
                            <h6 class="text-uppercase small fw-bold mb-3" style="color: #F2B50B; letter-spacing: 1px;">📝 Tugas</h6>
                            @forelse($meeting->assignments as $a)
                                <div class="d-flex align-items-center p-2 mb-2 bg-white rounded border item-hover-card" 
                                     style="cursor: pointer; transition: all 0.2s;"
                                     onclick="window.location='{{ route('siswa.assignments.index') }}'">
                                    <i class="fas fa-tasks text-warning me-3 fa-lg"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">{{ $a->title }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Deadline: {{ \Carbon\Carbon::parse($a->due_at)->format('d M Y, H:i') }}</div>
                                    </div>
                                    <span class="btn btn-sm btn-outline-warning">Kerjakan</span>
                                </div>
                            @empty
                                <div class="text-muted small italic p-2">Tidak ada tugas khusus untuk pertemuan ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Standalone Materials if any -->
        @if(!$standaloneMaterials->isEmpty())
            <h5 class="mt-5 mb-4 text-muted"><i class="fas fa-folder-open me-2"></i> Materi Lainnya</h5>
            <div class="row">
                @foreach($standaloneMaterials as $m)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100" style="border-top: 4px solid #F2B50B;">
                            <div class="card-body">
                                <h5 class="card-title h6" style="color: #25671E;">{{ $m->title }}</h5>
                                <div class="small text-muted mb-3">
                                    <span class="me-2"><i class="fas fa-book me-1"></i> {{ $m->subject->name }}</span>
                                    <span><i class="fas fa-calendar me-1"></i> {{ $m->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-muted small mb-3">{{ Str::limit($m->content, 100) }}</p>
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
    @endif
@endsection

@push('styles')
<style>
    .item-hover-card:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border-color: #25671E !important;
    }
</style>
@endpush
