@extends('layouts.lms')

@section('title', 'Kelola Jam Pelajaran')

@section('content')
<div class="timeslots-page">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <a href="{{ route('tatausaha.schedules.index', ['academic_year_id' => $selectedYearId]) }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Jadwal
            </a>
            <h1 class="h3 mb-2 mt-2">⏰ Kelola Jam Pelajaran</h1>
            <p class="text-muted mb-0">Atur jam pelajaran dan waktu istirahat yang berlaku untuk seluruh kelas.</p>
        </div>
    </div>

    <!-- Filter Tahun Ajar -->
    <form method="GET" action="{{ route('tatausaha.schedules.time-slots') }}" class="card mb-4 filter-card" style="border-top: 4px solid #F2B50B;">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted"><i class="fas fa-calendar me-1"></i> Tahun Ajar</label>
                    <select name="academic_year_id" class="form-select form-select-sm ts-select">
                        <option value="">Pilih Tahun Ajar</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" @selected($selectedYearId == $ay->id)>
                                {{ $ay->name }} {{ $ay->is_active ? '✅ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" style="background-color: #25671E; border: none;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if($selectedYearId)
        <div class="row g-4">
            <!-- Tabel Existing Slots -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm" style="cursor: default;">
                    <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #25671E, #48A111); border: none;">
                        <h6 class="mb-0 text-white fw-bold"><i class="fas fa-list me-2"></i> Daftar Jam Pelajaran</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($timeSlots->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                            <th class="ps-4" style="width: 50px; color: #6c757d; font-weight: 600; font-size: 0.8rem;">URUTAN</th>
                                            <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">LABEL</th>
                                            <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">TIPE</th>
                                            <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem;">WAKTU</th>
                                            <th style="color: #6c757d; font-weight: 600; font-size: 0.8rem; text-align: center; width: 80px;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($timeSlots as $slot)
                                            <tr class="{{ $slot->isBreak() ? 'break-row-highlight' : '' }}">
                                                <td class="ps-4 text-center fw-bold" style="color: #25671E;">{{ $slot->slot_order }}</td>
                                                <td>
                                                    <span class="fw-semibold" style="color: {{ $slot->isBreak() ? '#b8860b' : '#25671E' }};">
                                                        @if($slot->isBreak()) ☕ @else 📖 @endif
                                                        {{ $slot->label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($slot->isBreak())
                                                        <span class="badge" style="background-color: #F2B50B; color: #333; font-size: 0.7rem;">Istirahat</span>
                                                    @else
                                                        <span class="badge" style="background-color: #48A111; font-size: 0.7rem;">Pelajaran</span>
                                                    @endif
                                                </td>
                                                <td class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                </td>
                                                <td class="text-center">
                                                    <form method="POST" action="{{ route('tatausaha.schedules.time-slots.destroy', $slot) }}" class="d-inline" onsubmit="return confirm('Hapus slot ini? Jadwal terkait juga akan terhapus.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-clock fa-3x mb-3" style="color: #e0e0e0;"></i>
                                <p class="text-muted">Belum ada jam pelajaran. Tambahkan di form samping.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Tambah Slot -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm" style="cursor: default;">
                    <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #F2B50B, #f5c842); border: none;">
                        <h6 class="mb-0 fw-bold" style="color: #333;"><i class="fas fa-plus-circle me-2"></i> Tambah / Edit Slot</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('tatausaha.schedules.time-slots.store') }}">
                            @csrf
                            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Urutan Slot</label>
                                <input type="number" name="slot_order" class="form-control form-control-sm" 
                                       value="{{ $timeSlots->count() + 1 }}" min="1" required
                                       placeholder="Nomor urut (misal: 1, 2, 3...)">
                                <div class="form-text">Jika urutan sudah ada, data akan diupdate.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tipe</label>
                                <select name="type" class="form-select form-select-sm" id="slotType" onchange="updateLabel()">
                                    <option value="lesson">📖 Pelajaran</option>
                                    <option value="break">☕ Istirahat</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Label</label>
                                <input type="text" name="label" class="form-control form-control-sm" id="slotLabel" 
                                       placeholder="Misal: Jam ke-1, Istirahat 1" required>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Mulai</label>
                                    <input type="time" name="start_time" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Selesai</label>
                                    <input type="time" name="end_time" class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm w-100 shadow-sm" style="background: linear-gradient(135deg, #25671E, #48A111); color: white; border: none;">
                                <i class="fas fa-save me-1"></i> Simpan Slot
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card border-0 shadow-sm mt-3" style="cursor: default;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: #25671E;"><i class="fas fa-info-circle me-1"></i> Panduan</h6>
                        <ul class="small text-muted mb-0" style="padding-left: 1.2rem;">
                            <li class="mb-2">Jam pelajaran digunakan sebagai <strong>baris</strong> di jadwal mingguan</li>
                            <li class="mb-2">Tipe <strong>Istirahat</strong> akan ditampilkan sebagai baris kosong</li>
                            <li class="mb-2">Jika <strong>urutan</strong> sudah ada, data slot akan diperbarui</li>
                            <li>Menghapus slot akan menghapus jadwal terkait</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card text-center py-5 border-0 shadow-sm" style="cursor: default;">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-4x mb-4" style="color: #e0e0e0;"></i>
                <h5 class="mb-2" style="color: #25671E;">Pilih Tahun Ajar</h5>
                <p class="text-muted">Pilih tahun ajar terlebih dahulu.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .timeslots-page .filter-card:hover,
    .timeslots-page .card:hover {
        transform: none !important;
        cursor: default;
    }

    .break-row-highlight {
        background-color: rgba(242, 181, 11, 0.08);
    }

    .break-row-highlight td {
        border-left: 3px solid #F2B50B !important;
    }
</style>

<script>
    function updateLabel() {
        const type = document.getElementById('slotType').value;
        const label = document.getElementById('slotLabel');
        if (type === 'break' && !label.value) {
            label.placeholder = 'Misal: Istirahat 1';
        } else if (!label.value) {
            label.placeholder = 'Misal: Jam ke-1';
        }
    }
</script>
@endsection
