@extends('layouts.lms')

@section('title', 'Catatan Perilaku - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.index') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">📝 Catatan Perilaku Siswa - {{ $class->name }}</h1>
        <p class="text-muted mb-0">Kelola catatan perilaku positif dan negatif siswa</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tombol Tambah Catatan -->
    <div class="mb-4">
        <a href="{{ route('guru.classroom.behavior.create', $class) }}" class="btn btn-lg" style="background-color: #48A111; color: white; border: none;">
            ➕ Tambah Catatan Perilaku
        </a>
    </div>

    <!-- Daftar Catatan Perilaku -->
    <div class="card">
        <div class="card-body p-4">
            @if($behaviors->count() > 0)
                <div class="space-y-3">
                    @foreach($behaviors as $behavior)
                        <div class="p-3 border rounded" style="border-left: 4px solid {{ $behavior->type === 'positif' ? '#48A111' : '#FF6B6B' }};">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-2">
                                        {{ $behavior->type === 'positif' ? '✓' : '⚠️' }} 
                                        <strong style="color: #25671E;">{{ $behavior->student->user->name }}</strong>
                                    </h6>
                                    <h5 style="color: #25671E; margin: 0.5rem 0;">{{ $behavior->title }}</h5>
                                    <p class="mb-2 text-muted">{{ $behavior->description }}</p>
                                    <small class="text-muted">
                                        📅 {{ $behavior->date->translatedFormat('d F Y') }} | 
                                        <span style="color: {{ $behavior->type === 'positif' ? '#48A111' : '#FF6B6B' }}; font-weight: 600;">
                                            {{ $behavior->type === 'positif' ? '✓ Positif' : '⚠️ Negatif' }}
                                        </span>
                                    </small>
                                </div>
                                <form method="POST" action="{{ route('guru.classroom.behavior.destroy', [$class, $behavior]) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $behaviors->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">📭 Belum ada catatan perilaku.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
