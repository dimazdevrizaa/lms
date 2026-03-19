@extends('layouts.lms')

@section('title', 'Input Absensi - ' . $class->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('guru.classroom.attendance', $class) }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-2 mt-2">🖊️ Input Absensi - {{ $class->name }}</h1>
        <p class="text-muted mb-0">Catat absensi harian kelas perwalian Anda</p>
    </div>

    <form method="POST" action="{{ route('guru.classroom.attendance.store', $class) }}">
        @csrf

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Konfigurasi</h5>
                        
                        <!-- Tanggal Absensi -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #25671E;">📅 Tanggal</label>
                            <input class="form-control" style="border-color: #25671E;" type="date" name="date" value="{{ old('date', $today) }}" required>
                            @error('date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="alert alert-info border-0 small">
                            <i class="fas fa-info-circle me-1"></i>
                            Absensi ini adalah absensi kehadiran harian kelas.
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-lg" style="background-color: #25671E; color: white; border: none;" type="submit">
                        <i class="fas fa-check-circle me-2"></i> Simpan Absensi
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('guru.classroom.attendance', $class) }}">Batal</a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Status Kehadiran Siswa</h5>
                            <button type="button" class="btn btn-sm btn-outline-success" id="markAllHadir">
                                <i class="fas fa-check-double me-1"></i> Semua Hadir
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead style="background-color: #F7F0F0;">
                                    <tr>
                                        <th style="color: #25671E; width: 50px;">No</th>
                                        <th style="color: #25671E;">Nama Siswa</th>
                                        <th style="color: #25671E; text-align: center; width: 280px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $student->user->name }}</strong><br>
                                                <small class="text-muted">NIS: {{ $student->nis }}</small>
                                            </td>
                                            <td>
                                                <div class="status-radio-group">
                                                    <div class="status-radio-item">
                                                        <input type="radio" name="statuses[{{ $student->id }}]" id="h_{{ $student->id }}" value="hadir" checked required>
                                                        <label for="h_{{ $student->id }}">HADIR</label>
                                                    </div>
                                                    <div class="status-radio-item">
                                                        <input type="radio" name="statuses[{{ $student->id }}]" id="i_{{ $student->id }}" value="izin" required>
                                                        <label for="i_{{ $student->id }}">IZIN</label>
                                                    </div>
                                                    <div class="status-radio-item">
                                                        <input type="radio" name="statuses[{{ $student->id }}]" id="s_{{ $student->id }}" value="sakit" required>
                                                        <label for="s_{{ $student->id }}">SAKIT</label>
                                                    </div>
                                                    <div class="status-radio-item">
                                                        <input type="radio" name="statuses[{{ $student->id }}]" id="a_{{ $student->id }}" value="alpa" required>
                                                        <label for="a_{{ $student->id }}">ALPA</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('statuses')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .status-radio-group {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .status-radio-item {
            flex: 1;
        }
        .status-radio-item input[type="radio"] {
            display: none;
        }
        .status-radio-item label {
            display: block;
            padding: 5px 2px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        /* Hadir */
        .status-radio-item input[value="hadir"]:checked + label {
            background-color: #25671E;
            color: white;
            border-color: #25671E;
        }
        /* Izin */
        .status-radio-item input[value="izin"]:checked + label {
            background-color: #F2B50B;
            color: #25671E;
            border-color: #F2B50B;
        }
        /* Sakit */
        .status-radio-item input[value="sakit"]:checked + label {
            background-color: #48A111;
            color: white;
            border-color: #48A111;
        }
        /* Alpa */
        .status-radio-item input[value="alpa"]:checked + label {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .status-radio-item label:hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        document.getElementById('markAllHadir').addEventListener('click', () => {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="hadir"]');
            radioButtons.forEach(radio => radio.checked = true);
        });
    </script>
@endsection
