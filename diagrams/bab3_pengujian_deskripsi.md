# DRAFT DESKRIPSI DIAGRAM (SUB-BAB E: TAHAP PENGUJIAN PRODUK) - BAB III METODOLOGI PENELITIAN
**Judul Penelitian:** Perancangan dan Implementasi Learning Management System dengan Fitur Monitoring Orang Tua di SMAN 15 Padang
**Teknologi:** Laravel, Tailwind CSS, MySQL, Vite

---

## E. Tahap Pengujian Produk

Pengujian produk atau pengujian sistem dilakukan untuk memastikan bahwa Sistem Informasi Learning Management System (LMS) Berbasis Web pada SMA Negeri 15 Padang bekerja sesuai kebutuhan pengguna dan bebas dari kesalahan. Dalam penelitian ini digunakan dua metode pengujian, yaitu Uji alpha dan Uji Beta.

### 1. Pengujian Alpha (Alpha Testing)

Uji Alpha adalah tahap pengujian perangkat lunak, di mana Alpha Testing dilakukan internal oleh tim pengembang menggunakan teknik Black Box (fokus fungsionalitas) dan White Box (struktur internal) **(Zen & Nuryasin, 2024)**.

#### a. Pengujian Whitebox testing
Pengujian white box merupakan metode pengujian yang dilakukan dengan menguji struktur logika internal pada sistem Learning Management System (LMS) berbasis web. Pengujian ini disusun berdasarkan alur program, percabangan, dan proses perulangan agar setiap bagian sistem dapat diperiksa secara menyeluruh **(Zen & Nuryasin, 2024)**.

Tahapan pengujian whitebox yaitu sebagai berikut **(Tanod et al., 2025)**:
* **a. Flowgraph**: Flowgraph merupakan gambaran visual alur logika pada sistem Learning Management System (LMS) berbasis web yang menunjukkan urutan proses dalam program. Flowgraph terdiri dari node sebagai penanda proses dan edge sebagai penghubung alur kontrol, sehingga memudahkan analisis struktur sistem sebelum dilakukan pengujian.
* **b. Perhitungan Cyclomatic Complexity (CC)**: Cyclomatic Complexity atau V(G) digunakan untuk mengetahui tingkat kerumitan logika pada sistem Learning Management System (LMS) berbasis web serta menentukan jumlah jalur pengujian yang perlu dilakukan. Nilai ini dapat dihitung menggunakan rumus sebagai berikut **(Tanod et al., 2025)**:
  $$V(G) = E - N + 2$$ atau $$V(G) = P + 1$$
  *Keterangan:*
  * $V(G)$ = Nilai Cyclomatic Complexity
  * $E$ = Jumlah edge pada flowgraph
  * $N$ = Jumlah node pada flowgraph
  * $P$ = Jumlah predicate node atau titik percabangan
* **c. Penyusunan test case**: Penyusunan test case dilakukan untuk menguji setiap fitur pada sistem Learning Management System (LMS) berbasis web. Test case dibuat berdasarkan alur sistem dan kebutuhan pengguna, serta memuat langkah pengujian dan hasil yang diharapkan agar fungsi sistem dapat dievaluasi dengan baik.

**Tabel 15. Pengujian Whitebox**
| No | Deskripsi Pengujian | Jenis Pengujian |
| :--- | :--- | :--- |
| 1 | Proses autentikasi login dan pengaturan hak akses pengguna | White Box (Cyclomatic Complexity) |
| 2 | Pengujian pengelolaan data master sekolah (Guru, Siswa, Kelas, Mapel) | White Box (Cyclomatic Complexity) |
| 3 | Pengujian fungsi penugasan mengajar guru oleh admin | White Box (Cyclomatic Complexity) |
| 4 | Pengujian proses pembuatan pertemuan dan upload materi oleh guru | White Box (Cyclomatic Complexity) |
| 5 | Pengujian proses pencatatan absensi siswa oleh guru | White Box (Cyclomatic Complexity) |
| 6 | Pengujian pengunggahan berkas jawaban tugas oleh siswa | White Box (Cyclomatic Complexity) |
| 7 | Pengujian fungsi pemberian nilai dan catatan perilaku oleh guru | White Box (Cyclomatic Complexity) |
| 8 | Pengujian sistem monitoring rekap absensi, nilai, dan perilaku oleh orang tua | White Box (Cyclomatic Complexity) |

*(Zen & Nuryasin, 2024; Tanod et al., 2025)*

#### b. Pengujian Blackbox testing
Pada tahap ini digunakan metode black box testing untuk menguji fungsi sistem Learning Management System (LMS) berbasis web berdasarkan data masukan dan keluaran yang dihasilkan, tanpa memperhatikan struktur program **(Samdono et al., 2024)**. Pengujian dilakukan melalui beberapa skenario yang telah disusun, seperti proses login, manajemen data master, pembuatan kelas, pengelolaan materi, tugas, absensi, monitoring orang tua, dan logout. Setiap hasil pengujian dicatat sebagai berhasil atau tidak berhasil dengan kesesuaian hasil yang diperoleh. Tingkat keberhasilan pengujian alpha selanjutnya dihitung menggunakan rumus persentase **(Jamil & Setiawan, 2025)**:

$$P = \frac{\text{Jumlah fungsi yang berhasil diuji}}{\text{Jumlah seluruh fungsi yang diuji}} \times 100\%$$

*Keterangan:*
* $P$ = persentase keberhasilan pengujian (%)
* Jumlah fungsi yang berhasil diuji = jumlah skenario uji yang berjalan sesuai dengan hasil yang diharapkan
* Jumlah seluruh fungsi yang diuji = total skenario uji (test case) yang digunakan dalam pengujian

Melalui perhitungan tersebut, peneliti dapat mengetahui tingkat keberhasilan fungsi pada sistem Learning Management System (LMS) berbasis web. Semakin tinggi nilai persentase yang diperoleh, maka semakin baik kinerja sistem dalam menjalankan setiap fiturnya.
Untuk mempermudahkan penilaian persentase keberhasilan dikelompokkan ke dalam beberapa kategori penilaiannya sebagai berikut:

**Tabel 16. Interpretasi Skala Likert**
| Presentase | Interpretasi |
| :--- | :--- |
| 0% - 25% | Tidak baik |
| 26% - 50% | Kurang Baik |
| 51% - 75% | Baik |
| 76% - 100% | Sangat Baik |

*(Sumber: Samdono et al., 2024)*

Tabel ini digunakan untuk mengetahui tingkat keberhasilan pengujian, dan persentase kemudian diklasifikasikan dalam beberapa kategori.

**Tabel 17. Rencana Pengujian Alpha (Black Box)**
| No | Fungsi yang di uji | Deskripsi Singkat | Hasil yang diharapkan |
| :--- | :--- | :--- | :--- |
| 1 | Login Multi-Role | Pengguna masuk ke sistem menggunakan email dan password (atau parent code untuk Orang Tua) sesuai peran masing-masing. | Berhasil/Tidak Berhasil |
| 2 | Pengelolaan Data Master | Admin menambahkan, mengubah, dan menghapus data sekolah (Guru, Siswa, Kelas, Mapel, Tahun Akademik). | Berhasil/Tidak Berhasil |
| 3 | Penugasan Mengajar | Admin mengatur alokasi kelas mengajar dan mata pelajaran bagi guru. | Berhasil/Tidak Berhasil |
| 4 | Pembuatan Pertemuan | Guru membuat sesi pertemuan baru untuk kelas yang diampu. | Berhasil/Tidak Berhasil |
| 5 | Upload Materi Ajar | Guru mengunggah file materi pembelajaran per pertemuan. | Berhasil/Tidak Berhasil |
| 6 | Pembuatan Tugas | Guru membuat tugas baru beserta menentukan batas waktu pengumpulannya. | Berhasil/Tidak Berhasil |
| 7 | Pengumpulan Tugas | Siswa mengunggah berkas jawaban tugas sebelum batas waktu yang ditentukan. | Berhasil/Tidak Berhasil |
| 8 | Penilaian Tugas | Guru memberikan nilai angka dan umpan balik pada jawaban tugas siswa. | Berhasil/Tidak Berhasil |
| 9 | Pencatatan Kehadiran | Guru menginput status absensi kehadiran siswa per sesi pertemuan. | Berhasil/Tidak Berhasil |
| 10 | Pengisian Catatan Perilaku | Guru menginput poin catatan perilaku siswa di kelas. | Berhasil/Tidak Berhasil |
| 11 | Monitoring Orang Tua | Orang Tua memantau rekap absensi, nilai tugas, dan catatan perilaku anak secara *real-time*. | Berhasil/Tidak Berhasil |
| 12 | Logout Akun | Pengguna keluar dari sistem dan sesi aktif dihapus dengan aman. | Berhasil/Tidak Berhasil |

---

### 2. Pengujian Beta (Beta Testing)

Pengujian beta (Beta Testing) merupakan tahap pengujian yang melibatkan pihak ketiga atau pengguna eksternal untuk menilai sistem **(Aqmarina et al., 2024)**. Pada tahap ini, proses pengujian dilakukan dengan penyusunan kuesioner yang kemudian dibagikan kepada responden melalui Google form. Setiap responden memberikan penilaiannya terhadap sistem melalui angket berbasis skala Likert. Selanjutnya, seluruh hasil jawaban dianalisis menggunakan rumus perhitungan tertentu untuk mengetahui tingkat penerimaan, efektivitas, serta kualitas sistem yang telah dirancang. Uji Beta dilakukan kepada sejumlah pengguna yang mewakili kondisi nyata di lapangan. Pada tahap ini, para responden diminta untuk mencoba seluruh fungsi sistem secara langsung untuk menilai:
* a. Kemudahan penggunaan antarmuka (UI/UX) pada dashboard.
* b. Kejelasan materi, tugas, dan rekap informasi akademik yang ditampilkan sistem.
* c. Kemudahan guru dalam menginput absensi dan nilai tugas.
* d. Kemudahan siswa dalam mengunggah berkas jawaban tugas.
* e. Keandalan sistem saat memperbarui grafik perkembangan anak bagi orang tua secara real-time.
* f. Respon sistem terhadap input salah atau kondisi error.

Dari hasil percobaan seluruh fungsi sistem sehingga diperoleh gambaran tentang apakah sistem telah berfungsi sesuai kebutuhan dan layak diterapkan dalam pengelolaan pembelajaran sekolah.

#### a. Skala Penilaian (Skala Likert)
Untuk menilai tingkat kelayakan dan penerimaan pengguna terhadap sistem informasi Learning Management System (LMS) berbasis web yang telah dirancang, dilakukan pengumpulan data melalui angket yang diberikan kepada responden. Angket digunakan untuk memperoleh penilaian langsung dari pengguna secara daring melalui Google Form dan untuk pengujian tenaga ahli angket diberikan dalam bentuk cetak (hardcopy) untuk memperoleh evaluasi secara lebih mendalam sesuai bidang keahlian masing-masing, terkait kemudahan penggunaan, fungsi sistem, keandalan, serta kinerja sistem secara keseluruhan. Setiap pernyataan dalam angket dinilai menggunakan skala Likert sehingga hasilnya dapat dianalisis secara kuantitatif dalam bentuk persentase. Persentase tersebut kemudian digunakan untuk menentukan tingkat kelayakan sistem sesuai kategori penilaian yang telah ditentukan.

**Tabel 18. skala likert**
| Tingkat Kelayakan | Skala |
| :--- | :--- |
| Sangat Setuju | 4 |
| Setuju | 3 |
| Kurang Setuju | 2 |
| Tidak Setuju | 1 |

Perhitungan skor pada angket dilakukan menggunakan rumus **(Samdono et al., 2024)**:

$$Y = \frac{\sum (N \times R)}{\text{Skor Ideal}} \times 100\%$$

*Keterangan:*
* $Y$ = Nilai Persentase yang dicari
* $N$ = Nilai dari setiap jawaban
* $R$ = Frekuensi
* Skor Ideal = Jumlah dari soal atau penilaian (Skor tertinggi $\times$ jumlah responden $\times$ jumlah pertanyaan)

Setelah nilai perhitungan diperoleh, tahap berikutnya adalah mengkonversi persentase tersebut ke dalam kategori penilaian yang telah ditetapkan. Pedoman ini digunakan untuk menentukan tingkat kelayakan berdasarkan nilai akhir yang dihasilkan.

**Tabel 19. Interpretasi Skala Likert**
| Presentase | Interpretasi |
| :--- | :--- |
| 0% - 25% | Tidak baik |
| 26% - 50% | Kurang Baik |
| 51% - 75% | Baik |
| 76% - 100% | Sangat Baik |

*(Sumber: Samdono et al., 2024)*

#### b. Pengujian Tenaga Ahli
Pengujian beta oleh tenaga ahli dilakukan untuk menilai apakah sistem informasi Learning Management System (LMS) berbasis web telah memenuhi kebutuhan fungsional dan nonfungsional. Pada tahap ini, tenaga ahli di bidang sistem informasi diminta untuk mencoba sistem dan mengevaluasi alur proses KBM serta monitoring pembelajaran. Hasil pengujian digunakan untuk menilai kinerja sistem dan memastikan bahwa sistem layak digunakan. Berikut kisi-kisi instrumen pengujian yang digunakan untuk penilaian oleh tenaga ahli **(Aqmarina et al., 2024)**:

**Tabel 20. Kisi-kisi Instrumen Pengujian Tenaga Ahli**
| No | Aspek | Indikator | Nomor Pernyataan |
| :--- | :--- | :--- | :--- |
| 1 | Fungsionalitas (*Functionality*) | a. Fungsi fitur Sistem<br>b. Kesesuaian hasil pengolahan data | 1,2 |
| 2 | Keandalan (*Reliability*) | a. Kemampuan toleransi sistem terhadap kesalahan<br>b. Kemampuan sistem menangani dan memulihkan kesalahan | 3,4 |
| 3 | Kegunaan (*Usability*) | a. Kemudahan dalam operasional sistem<br>b. Kejelasan tampilan informasi | 5,6 |
| 4 | Efisiensi (*Efficiency*) | a. Waktu yang dibutuhkan sistem untuk merespon aktivitas pengguna | 7 |

*(Sumber: Aqmarina et al., 2024)*

#### c. Pengujian Pengguna
Selain pengujian oleh tenaga ahli, dilakukan juga pengujian beta oleh pengguna sistem, yaitu perwakilan guru, siswa, dan orang tua siswa yang terlibat dalam aktivitas sistem. Pengujian ini bertujuan untuk mengetahui kemudahan penggunaan sistem, kejelasan informasi yang ditampilkan, serta sejauh mana sistem membantu proses pembelajaran dan monitoring perkembangan akademik siswa. Instrumen pengujian disusun berdasarkan beberapa aspek penilaian yang telah ditentukan **(Samdono et al., 2024)**.

**Tabel 21. Kisi-kisi instrumen penelitian pengguna**
| No | Aspek | Indikator | Nomor Pernyataan |
| :--- | :--- | :--- | :--- |
| 1 | Isi (*Content*) | a. Kesesuaian informasi yang dihasilkan<br>b. Kelengkapan informasi yang dihasilkan | 1,2,3,4 |
| 2 | Keakuratan (*Accuracy*) | a. Kebenaran informasi yang dihasilkan | 5,6 |
| 3 | Bentuk (*Format*) | a. Kualitas tampilan pada sistem | 7,8,9,10,11 |
| 4 | Kemudahan (*Ease of use*) | a. Kemudahan dalam menggunakan sistem | 12,13 |
| 5 | Pemeliharaan (*Maintainability*) | a. Kemampuan yang dibutuhkan sistem untuk mengetahui terjadinya kesalahan<br>b. Kemudahan dalam memperbaiki sistem | 8,9 |
| 6 | Portabilitas (*Portability*) | a. Kemudahan dalam menyebarkan sistem | 10,11,12,13,14 |
| 7 | Ketepatan waktu (*Timeliness*) | a. Waktu yang dibutuhkan sistem dalam merespon<br>b. Informasi yang dihasilkan merupakan informasi terbaru dengan tepat waktu | 14,15 |

*(Sumber: Samdono et al., 2024)*

---

## DAFTAR PUSTAKA (REFERENSI PENGUJIAN)

1. Aqmarina, A. S., Aditiawan, F. P., & Wahanani, H. E. (2024). Pengujian Sistem Informasi Perpustakaan Sma Wijaya Putra Surabaya Menggunakan Metode Black Box Testing Dengan Teknik Equivalence Partitioning Dan Boundary Value Analysis. *JATI (Jurnal Mahasiswa Teknik Informatika)*, 8(1), 855-860.
2. Jamil, M., & Setiawan, R. (2025). Pengujian Black Box Testing pada Fitur Permohonan Informasi Publik (Studi Kasus: Website PPID). *Jurnal Edukasi dan Riset Informatika (JERKIN)*, 5(1).
3. Samdono, A., Sari, A. P., & Aditiawan, F. P. (2024). Pengujian Black Box Pada Sistem Informasi Stok Dan Penjualan Berbasis Website Menggunakan Metode Equivalence Partitioning. *JATI (Jurnal Mahasiswa Teknik Informatika)*, 8(1), 880-885.
4. Tanod, M. V., Angriani, H., & Afifah. (2025). Pengujian White Box Basis Path Testing Fitur Menghitung Aplikasi ComfyLearn. *JTRISTE (Jurnal Ilmiah Teknologi Informasi dan Sains)*, 13(1).
5. Zen, H. R. R., & Nuryasin, I. (2024). Penerapan Whitebox Testing pada Pengujian Sistem Menggunakan Teknik Basis Path. *JOISIE (Journal Of Information Systems and Informatics Engineering)*, 8(1).
