flowchart TD
    Start([Mulai]) --> FormLogin[/Halaman Login/]
    FormLogin --> InputData[/Input Email & Password/]
    InputData --> CekData{Cek Database?}
    
    CekData -- Tidak Valid --> PesanError[/Tampilkan Pesan Error/]
    PesanError --> FormLogin
    
    CekData -- Valid --> CekRole{Cek Role User}
    
    CekRole -- Admin --> DashboardAdmin[Halaman Admin]
    DashboardAdmin --> KelolaMaster[Kelola Data Master & Laporan]
    
    CekRole -- Guru --> DashboardGuru[Halaman Guru]
    DashboardGuru --> KelolaKBM[Kelola Pembelajaran, Absensi, Nilai]
    
    CekRole -- Siswa --> DashboardSiswa[Halaman Siswa]
    DashboardSiswa --> AksesKBM[Akses Materi & Tugas]
    
    CekRole -- Orang Tua --> DashboardOrtu[Halaman Orang Tua]
    DashboardOrtu --> PantauAnak[Pantau Absensi, Perilaku & Nilai]
    
    KelolaMaster --> Selesai([Selesai])
    KelolaKBM --> Selesai
    AksesKBM --> Selesai
    PantauAnak --> Selesai
