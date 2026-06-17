# DRAFT DESKRIPSI DIAGRAM (UML & FLOWCHART) - BAB III METODOLOGI PENELITIAN
**Judul Penelitian:** Perancangan dan Implementasi Learning Management System dengan Fitur Monitoring Orang Tua di SMAN 15 Padang
**Teknologi:** Laravel, Tailwind CSS, MySQL, Vite

---

## D. Perancangan Sistem (UML & Flowchart)

Pada tahap perancangan ini, penulis memodelkan sistem menggunakan UML (Unified Modeling Language) dan membuat flowchart sistem. Pemodelan ini bertujuan untuk mempermudah pemahaman tentang bagaimana pengguna berinteraksi dengan sistem, bagaimana data mengalir, dan bagaimana struktur basis data yang akan dibangun.

### 1. Use Case Diagram

Use Case Diagram digunakan untuk menunjukkan fitur-fitur apa saja yang dapat diakses oleh setiap pengguna (aktor) di dalam sistem LMS SMAN 15 Padang.

*(Sisipkan Gambar Use Case Diagram di sini)*
**Gambar 3.X.** Use Case Diagram LMS SMAN 15 Padang

Pada Gambar 3.X, terdapat empat aktor yang memiliki hak akses berbeda di dalam sistem. Aktor pertama adalah Admin (staf tata usaha) yang bertugas mengelola data sekolah, seperti tahun akademik, kelas, mata pelajaran, serta data guru dan siswa. Admin juga bertugas membuatkan akun login untuk guru, siswa, dan orang tua, serta mengatur pembagian kelas mengajar bagi guru. Melalui dashboard, admin dapat melihat ringkasan statistik data sekolah secara umum.

Aktor kedua adalah Guru yang berfungsi mengelola kelas dan materi pembelajaran. Guru dapat membuat pertemuan baru, mengunggah materi belajar, dan membuat tugas. Guru juga bertugas mencatat kehadiran siswa di kelas, memeriksa dan memberikan nilai pada tugas yang dikumpulkan siswa, serta menulis catatan perilaku siswa jika diperlukan.

Aktor ketiga adalah Siswa yang menggunakan sistem untuk kebutuhan belajar. Siswa dapat melihat jadwal pelajaran, mengunduh materi dari guru, serta mengumpulkan tugas secara online. Siswa juga dapat melihat rekapitulasi kehadiran dan nilai tugas yang mereka peroleh melalui dashboard pribadi.

Aktor keempat adalah Orang Tua yang berfungsi memantau aktivitas belajar anaknya secara langsung. Orang tua login menggunakan email dan kode khusus (parent code) anak mereka. Melalui sistem ini, orang tua bisa memantau kehadiran anak, melihat status pengumpulan tugas dan nilai anak, serta membaca catatan perilaku anak yang ditulis oleh guru.

---

### 2. Activity Diagram

Activity Diagram digunakan untuk menggambarkan alur aktivitas atau proses bisnis yang berjalan di dalam sistem.

#### a. Activity Diagram Login (Multi-Role)
Diagram ini menjelaskan alur proses saat pengguna masuk ke dalam sistem.
*(Sisipkan Gambar 02_Activity_Login.drawio di sini)*
**Gambar 3.X.** Activity Diagram Login

Proses login dimulai saat pengguna membuka halaman login LMS dan memasukkan email serta password. Khusus untuk orang tua, kredensial yang dimasukkan adalah email dan kode orang tua (parent code) anak. Sistem kemudian memeriksa data tersebut ke database. Jika data yang dimasukkan salah, sistem akan menampilkan pesan kesalahan dan pengguna diminta mengisi ulang. Jika data benar, sistem akan membaca peran pengguna (role) dan mengarahkannya ke halaman dashboard masing-masing.

#### b. Activity Diagram Kelola Data Master (Admin)
Diagram ini menggambarkan alur kerja admin saat mengelola data sekolah.
*(Sisipkan Gambar 03_Activity_Admin.drawio di sini)*
**Gambar 3.X.** Activity Diagram Kelola Data Master Admin

Aktivitas ini dimulai ketika admin masuk ke menu kelola data master (bisa berupa data guru, siswa, kelas, atau mata pelajaran). Admin kemudian memilih opsi tambah, edit, atau hapus data. Sistem akan menampilkan form input yang sesuai, memproses data yang diisi admin, lalu menyimpannya ke database. Setelah data tersimpan, sistem akan menampilkan kembali daftar data yang paling baru di layar admin.

#### c. Activity Diagram Kelola Pembelajaran dan Evaluasi (Guru)
Diagram ini menggambarkan aktivitas guru saat mengelola kelas dan menginput nilai.
*(Sisipkan Gambar 04_Activity_Guru.drawio di sini)*
**Gambar 3.X.** Activity Diagram Kelola Pembelajaran dan Evaluasi Guru

Proses dimulai saat guru memilih kelas dan mata pelajaran yang diajarkannya. Guru kemudian bisa membuat sesi pertemuan baru, mengunggah materi, membuat tugas, atau mengisi absensi kehadiran siswa. Selain itu, guru juga dapat memeriksa tugas yang dikirim siswa untuk diberi nilai, serta menulis catatan perilaku siswa. Semua inputan dari guru akan divalidasi oleh sistem dan disimpan ke dalam database.

#### d. Activity Diagram Akses Belajar dan Pengumpulan Tugas (Siswa)
Diagram ini menggambarkan alur aktivitas siswa saat belajar dan mengumpulkan tugas.
*(Sisipkan Gambar 05_Activity_Siswa.drawio di sini)*
**Gambar 3.X.** Activity Diagram Siswa

Aktivitas dimulai ketika siswa login dan memilih mata pelajaran yang ingin dipelajari. Sistem akan menampilkan daftar pertemuan yang sudah dibuat guru. Siswa dapat mengunduh materi ajar yang ada pada pertemuan tersebut. Jika ada tugas, siswa dapat mengunggah file jawabannya ke sistem. Sistem kemudian menyimpan file tersebut ke database dan mengubah status tugas siswa menjadi sudah dikumpulkan.

#### e. Activity Diagram Monitoring Orang Tua (Parent Monitoring)
Diagram ini menjelaskan alur orang tua saat memantau perkembangan belajar anak.
*(Sisipkan Gambar 06_Activity_OrangTua.drawio di sini)*
**Gambar 3.X.** Activity Diagram Monitoring Orang Tua

Alur dimulai ketika orang tua login menggunakan email dan kode orang tua (parent code). Sistem kemudian mendeteksi data siswa yang terhubung dengan kode tersebut. Setelah data ditemukan, sistem akan menarik data kehadiran, nilai tugas, dan catatan perilaku siswa dari database. Data-data tersebut kemudian disajikan secara langsung dalam bentuk grafik dan tabel informasi di dashboard orang tua.

---

### 3. Sequence Diagram

Sequence Diagram digunakan untuk menunjukkan interaksi antar-objek di dalam sistem secara berurutan berdasarkan waktu.

#### a. Sequence Diagram Admin (Kelola Data Master)
Diagram ini menggambarkan urutan interaksi admin saat mengelola data sistem.
*(Sisipkan Gambar 09_Sequence_Admin.drawio di sini)*
**Gambar 3.X.** Sequence Diagram Admin

Alur interaksi dimulai saat admin membuka halaman login melalui browser. Browser meminta halaman login ke sistem, dan sistem menampilkan form login. Admin memasukkan email serta password, lalu klik tombol login. Browser mengirimkan data login tersebut ke sistem. Sistem mencocokkan data ke database melalui query. Database mengirimkan kembali data user dengan role admin, lalu sistem mengarahkan browser untuk membuka dashboard admin. Ketika admin ingin menambah, mengedit, atau menghapus data master, admin menginput data pada form dan klik simpan. Browser mengirimkan data inputan tersebut ke sistem, lalu sistem memperbarui data di database. Database memberikan konfirmasi sukses, dan sistem menampilkan notifikasi berhasil kepada admin melalui layar browser.

#### b. Sequence Diagram Guru (Kelola KBM & Evaluasi)
Diagram ini menggambarkan interaksi guru dalam mengunggah materi, mengisi absensi, dan memberi nilai.
*(Sisipkan Gambar 10_Sequence_Guru.drawio di sini)*
**Gambar 3.X.** Sequence Diagram Guru

Proses dimulai setelah guru login dan masuk ke dashboard. Guru memilih kelas dan mata pelajaran, lalu browser meminta data ke sistem. Sistem mengambil data kelas dan pertemuan dari database untuk ditampilkan di form. Saat guru membuat pertemuan baru serta mengunggah materi dan tugas, browser mengirimkan data tersebut ke sistem. Sistem menyimpan data pertemuan ke database pada tabel meetings, materials, dan assignments. Setelah database memberikan respon sukses, guru dapat menginput absensi dan nilai tugas siswa di browser. Data inputan ini dikirim ke sistem untuk disimpan ke database pada tabel attendances, student_grades, dan behavior_records. Database menyimpan data tersebut dan sistem menampilkan notifikasi berhasil disimpan di layar guru.

#### c. Sequence Diagram Siswa (Akses & Submit Tugas)
Diagram ini menggambarkan interaksi siswa saat mengunduh materi dan mengumpulkan tugas.
*(Sisipkan Gambar 11_Sequence_Siswa.drawio di sini)*
**Gambar 3.X.** Sequence Diagram Siswa

Alur interaksi dimulai saat siswa login dan masuk ke dashboard. Siswa memilih mata pelajaran yang ingin diikuti, lalu browser meminta data ke sistem. Sistem mengambil berkas materi dan tugas dari database untuk ditampilkan ke siswa. Siswa kemudian dapat membaca dan mengunduh berkas tersebut. Untuk mengumpulkan tugas, siswa mengunggah berkas jawaban di halaman tugas dan klik kirim. Browser mengirimkan file jawaban ke sistem, lalu sistem menyimpannya ke tabel submissions di database. Setelah database selesai menyimpan, sistem memperbarui tampilan halaman tugas siswa menjadi sudah dikumpulkan.

#### d. Sequence Diagram Orang Tua (Monitoring Anak)
Diagram ini menggambarkan interaksi orang tua dalam memantau nilai dan absensi anak.
*(Sisipkan Gambar 12_Sequence_OrangTua.drawio di sini)*
**Gambar 3.X.** Sequence Diagram Orang Tua

Proses dimulai ketika orang tua memasukkan email dan kode orang tua (parent code) di form login. Browser mengirimkan data login tersebut ke sistem, lalu sistem memvalidasi data ke database. Database mengonfirmasi bahwa data tersebut cocok dengan data siswa, dan sistem mengarahkan browser membuka dashboard orang tua. Saat orang tua membuka menu monitoring, browser meminta data perkembangan anak ke sistem. Sistem melakukan pencarian data siswa di database berdasarkan kode orang tua. Setelah data siswa ditemukan, sistem menarik data rekap absensi, nilai tugas, dan catatan perilaku dari tabel attendances, student_grades, dan behavior_records di database. Database mengirimkan data tersebut ke sistem, lalu sistem merendernya dalam bentuk grafik di dashboard orang tua. Jika orang tua ingin melihat rincian kehadiran atau nilai, sistem akan melakukan query detail ke database dan menampilkan data rincian tersebut secara instan di browser orang tua.

---

### 4. Class Diagram

Class Diagram menggambarkan hubungan antar-tabel dan model data yang membentuk struktur sistem LMS.

*(Sisipkan Gambar 07_Class_Diagram.drawio di sini)*
**Gambar 3.X.** Class Diagram LMS SMAN 15 Padang

Berdasarkan rancangan kelas yang dibuat, struktur data sistem berpusat pada model `User` yang menyimpan data login dasar (email, password, dan role_id). Model `User` terhubung secara satu-ke-satu (one-to-one) dengan tabel profil pengguna, yaitu `AdminStaff` (staf admin), `Teacher` (guru), dan `Student` (siswa). Model `Student` memiliki relasi banyak-ke-satu (many-to-one) dengan `SchoolClass` (kelas), karena satu kelas dapat berisi banyak siswa. Model `Student` juga memiliki kolom `parent_code` yang digunakan untuk menghubungkan siswa dengan akun orang tuanya.

Struktur akademik sekolah diatur oleh model `SchoolClass` (kelas), `Subject` (mata pelajaran), dan `AcademicYear` (tahun akademik). Untuk mengatur jadwal mengajar guru, dibuat model penengah bernama `ClassSubjectTeacher` yang menghubungkan tabel `Teacher`, `SchoolClass`, dan `Subject`. Model `Meeting` (pertemuan kelas) dibuat di bawah `ClassSubjectTeacher`. Di dalam setiap pertemuan, guru dapat mengunggah berkas materi melalui model `Material` dan membuat tugas melalui model `Assignment`.

Pengerjaan tugas siswa dicatat pada tabel `AssignmentSubmission` yang terhubung dengan `Assignment` dan `Student` untuk menyimpan file jawaban, tanggal kirim, nilai, serta feedback dari guru. Proses absensi dikelola oleh model `Attendance` yang terhubung dengan `Meeting`, serta mencatat status kehadiran individu siswa (seperti Hadir, Sakit, Izin, Alfa) pada tabel `AttendanceDetail` yang terhubung dengan `Student`. Terakhir, guru dapat memberikan catatan perilaku siswa melalui model `BehaviorRecord` yang terhubung dengan `Student` dan `Teacher`, yang nantinya dapat dilihat oleh orang tua.

---

### 5. Flowchart Sistem

Flowchart Sistem menggambarkan alur logika program secara keseluruhan dari awal sistem dimulai hingga selesai.

*(Sisipkan Gambar 08_Flowchart_Sistem.drawio di sini)*
**Gambar 3.X.** Flowchart Sistem LMS SMAN 15 Padang

Alur logika sistem dimulai ketika pengguna membuka halaman utama website LMS dan melakukan login. Sistem akan memvalidasi email dan password yang dimasukkan. Jika salah, sistem akan menampilkan pesan error dan meminta pengguna mengisi kembali data login. Jika benar, sistem akan membaca tingkat hak akses pengguna. 

Pengguna dengan peran Admin diarahkan ke Dashboard Admin untuk mengelola data sekolah (data guru, siswa, kelas, mata pelajaran, tahun akademik, dan akun pengguna). Pengguna dengan peran Guru diarahkan ke Dashboard Guru untuk mengelola pertemuan kelas, mengunggah materi, membuat tugas, menginput absensi, menilai tugas, serta menulis catatan perilaku siswa. Pengguna dengan peran Siswa diarahkan ke Dashboard Siswa untuk mengunduh materi, mengumpulkan tugas, serta melihat rekap nilai dan kehadirannya. Pengguna dengan peran Orang Tua diarahkan ke Dashboard Orang Tua untuk memantau kehadiran, nilai tugas, dan catatan perilaku anak secara real-time. Setelah selesai menggunakan aplikasi, pengguna dapat melakukan logout untuk keluar dari sistem dan kembali ke halaman login utama.
