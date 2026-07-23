<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 font-weight-bold text-dark mb-1">
                        <i class="fas fa-clipboard-check text-success me-2"></i>Kelola Presensi Siswa (Admin)
                    </h2>
                    <p class="text-muted small mb-0">Pilih kelas di bawah ini untuk melihat dan mengelola presensi per mata pelajaran.</p>
                </div>
            </div>

            @if($classes->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="fas fa-school text-muted fa-3x mb-3"></i>
                    <h5 class="text-secondary fw-semibold">Belum Ada Data Kelas</h5>
                    <p class="text-muted small mb-0">Silakan tambahkan data kelas terlebih dahulu di menu Data Kelas.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($classes as $class)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover" style="border-left: 5px solid var(--primary, #0d6efd) !important;">
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-bold text-dark mb-0">{{ $class->name }}</h5>
                                            <span class="badge bg-light text-primary border rounded-pill px-3 py-1 font-monospace small">
                                                {{ $class->slug }}
                                            </span>
                                        </div>

                                        <div class="d-flex gap-3 text-muted small my-3">
                                            <div>
                                                <i class="fas fa-user-graduate text-primary me-1"></i>
                                                <strong>{{ $class->students_count }}</strong> Siswa
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar-alt text-success me-1"></i>
                                                <strong>{{ $class->meetings_count }}</strong> Pertemuan
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-top mt-3">
                                        <a href="{{ route('admin.attendances.showClass', $class->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-semibold">
                                            Pilih Kelas <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
