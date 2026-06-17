@extends('layouts.lms')

@section('title', 'Data Siswa - ' . $class->name)

@section('content')
    <div class="d-flex align-items-center gap-3 mb-5">
        <a href="{{ route('guru.classroom.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h1 class="h3 mb-1">👥 Data Siswa Kelas {{ $class->name }}</h1>
            <p class="text-muted mb-0">Total: <strong>{{ $students->count() }} siswa</strong></p>
        </div>
    </div>

    @if($students->count() > 0)
        <div class="card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #F7F0F0; border-bottom: 2px solid #25671E;">
                            <tr>
                                <th style="color: #25671E; font-weight: 600;">No.</th>
                                <th style="color: #25671E; font-weight: 600;">🆔 NIS</th>
                                <th style="color: #25671E; font-weight: 600;">👤 Nama</th>
                                <th style="color: #25671E; font-weight: 600;">📞 No. HP</th>
                                <th style="color: #25671E; font-weight: 600;">🔑 Akses Ortu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: #F2B50B; color: #25671E;">
                                            {{ $student->nis }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: #25671E;">{{ $student->user->name }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $student->phone ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 allow-copy">
                                            <code class="bg-light px-2 py-1 rounded text-dark" style="font-size: 0.85rem;">{{ $student->parent_code }}</code>
                                            <button class="btn btn-sm p-0 border-0 text-success copy-btn" data-code="{{ $student->parent_code }}" title="Salin Link Ortu">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <a href="{{ route('parent.view', $student->parent_code) }}" target="_blank" class="btn btn-sm p-0 border-0 text-primary" title="Buka Link Ortu">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada siswa di kelas ini.
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    alert('Kode akses orang tua berhasil disalin: ' + code);
                }).catch(err => {
                    console.error('Gagal menyalin kode: ', err);
                });
            });
        });
    });
</script>
@endpush
