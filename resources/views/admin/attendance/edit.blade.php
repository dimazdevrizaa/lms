@extends('layouts.lms')

@section('title', 'Edit Presensi - Pertemuan #' . $meeting->number)

@section('content')
<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.attendances.index') }}" class="text-decoration-none">Presensi</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attendances.showClass', $class->id) }}" class="text-decoration-none">{{ $class->name }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attendances.showSubject', ['class' => $class->id, 'subject' => $subject->id]) }}" class="text-decoration-none">{{ $subject->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pertemuan #{{ $meeting->number }}</li>
    </ol>
</nav>

<!-- Banner Header -->
<div class="content-card mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark, #0a58ca) 100%); border: none;">
    <div class="content-card-body p-4 position-relative">
        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
            <div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 mb-2 font-monospace">
                    Pertemuan #{{ $meeting->number }}
                </span>
                <h3 class="h4 font-weight-bold mb-1 text-white">{{ $subject->name }} — Kelas {{ $class->name }}</h3>
                <p class="mb-0 text-white-50 small">
                    Guru: <strong>{{ $meeting->teacher->user->name ?? '-' }}</strong> | 
                    Topik: <strong>{{ $meeting->title ?? $meeting->topic ?? '-' }}</strong>
                </p>
            </div>
            <a href="{{ route('admin.attendances.showSubject', ['class' => $class->id, 'subject' => $subject->id]) }}" class="btn btn-light text-primary btn-sm rounded-3 font-semibold">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Attendance Form -->
<form action="{{ route('admin.attendances.updateMeeting', $meeting->id) }}" method="POST">
    @csrf

    <div class="content-card mb-4">
        <div class="content-card-body p-4">
            <div class="row align-items-center mb-4 g-3">
                <div class="col-md-4">
                    <label for="date" class="form-label fw-bold text-secondary small text-uppercase">Tanggal Pertemuan</label>
                    <input type="date" id="date" name="date" class="form-control rounded-3" value="{{ old('date', $meeting->date) }}" required>
                </div>
                <div class="col-md-8 text-md-end">
                    <span class="text-muted small me-2">Tindakan Cepat:</span>
                    <button type="button" class="btn btn-outline-success btn-sm rounded-3 me-2" onclick="markAllStatus('hadir')">
                        <i class="fas fa-check-double me-1"></i> Tandai Semua Hadir
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" onclick="markAllStatus('alpa')">
                        <i class="fas fa-times-circle me-1"></i> Tandai Semua Alpa
                    </button>
                </div>
            </div>

            <!-- Student List Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 50px;">#</th>
                            <th class="py-3">Nama Siswa</th>
                            <th class="py-3" style="width: 140px;">NISN</th>
                            <th class="py-3 text-center" style="width: 110px;">Statistik Mapel</th>
                            <th class="py-3 text-center" style="width: 380px;">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php
                                $current = $existingStatus[$student->id] ?? 'hadir';
                                $stats = $studentStats[$student->id] ?? [];
                                $hadir = $stats['hadir'] ?? 0;
                                $izin = $stats['izin'] ?? 0;
                                $sakit = $stats['sakit'] ?? 0;
                                $alpa = $stats['alpa'] ?? 0;
                            @endphp
                            <tr>
                                <td class="ps-3 text-muted small fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $student->user->name ?? '-' }}</div>
                                </td>
                                <td class="text-muted font-monospace small">
                                    {{ $student->nisn ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <small class="text-muted">
                                        <span class="text-success fw-bold" title="Hadir">{{ $hadir }}H</span> ·
                                        <span class="text-warning fw-bold" title="Izin">{{ $izin }}I</span> ·
                                        <span class="text-info fw-bold" title="Sakit">{{ $sakit }}S</span> ·
                                        <span class="text-danger fw-bold" title="Alpa">{{ $alpa }}A</span>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group w-100" role="group" aria-label="Status Attendance">
                                        <input type="radio" class="btn-check status-radio" name="statuses[{{ $student->id }}]" id="hadir_{{ $student->id }}" value="hadir" {{ $current === 'hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm py-1 font-semibold" for="hadir_{{ $student->id }}">Hadir</label>

                                        <input type="radio" class="btn-check status-radio" name="statuses[{{ $student->id }}]" id="izin_{{ $student->id }}" value="izin" {{ $current === 'izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning btn-sm py-1 font-semibold" for="izin_{{ $student->id }}">Izin</label>

                                        <input type="radio" class="btn-check status-radio" name="statuses[{{ $student->id }}]" id="sakit_{{ $student->id }}" value="sakit" {{ $current === 'sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info btn-sm py-1 font-semibold" for="sakit_{{ $student->id }}">Sakit</label>

                                        <input type="radio" class="btn-check status-radio" name="statuses[{{ $student->id }}]" id="alpa_{{ $student->id }}" value="alpa" {{ $current === 'alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm py-1 font-semibold" for="alpa_{{ $student->id }}">Alpa</label>

                                        <input type="radio" class="btn-check status-radio" name="statuses[{{ $student->id }}]" id="cabut_{{ $student->id }}" value="cabut" {{ $current === 'cabut' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary btn-sm py-1 font-semibold" for="cabut_{{ $student->id }}">Cabut</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                <i class="fas fa-info-circle text-primary me-1"></i> Mode Admin Override: Menyimpan presensi ini akan memperbarui rekapitulasi kehadiran siswa.
            </span>
            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                <i class="fas fa-save me-1"></i> Simpan Presensi (Admin)
            </button>
        </div>
    </div>
</form>

<script>
    function markAllStatus(status) {
        document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection
