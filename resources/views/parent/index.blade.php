<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemantauan Orang Tua - SMAN 15 Padang</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #25671E;
            --secondary-color: #48A111;
            --accent-color: #F2B50B;
            --light-bg: #F7F0F0;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
        }

        .access-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(37, 103, 30, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            transition: all 0.3s ease;
        }

        .card-header-accent {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 35px 30px;
            text-align: center;
        }

        .btn-access {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-access:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 161, 17, 0.2);
        }

        .form-control-access {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .form-control-access:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(72, 161, 17, 0.15);
        }

        .info-box {
            background-color: #fcf8e3;
            border-left: 4px solid var(--accent-color);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #66512c;
        }

        .rate-limit-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #856404;
        }
    </style>
</head>
<body>

    <div class="access-card">
        <div class="card-header-accent">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA 15 Padang" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid white;" class="mb-3">
            <h4 class="mb-1 fw-bold">Pemantauan Orang Tua</h4>
            <p class="mb-0 small text-white-50">LMS SMA Negeri 15 Padang</p>
        </div>
        <div class="card-body p-4">
            <p class="text-center text-muted mb-4">
                Masukkan <strong>Kode Akses Orang Tua</strong> yang diberikan oleh wali kelas untuk memantau perkembangan belajar putra/putri Anda.
            </p>

            @if($errors->any())
                <div class="alert alert-danger border-0 small mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success border-0 small mb-3 text-center">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Rate Limit Warning (shown when Laravel returns 429) --}}
            @if(session('status') === 'throttled' || $errors->has('throttle'))
                <div class="rate-limit-warning">
                    <i class="fas fa-shield-alt me-1"></i>
                    <strong>Terlalu banyak percobaan.</strong> Silakan tunggu 1 menit sebelum mencoba lagi.
                </div>
            @endif

            <form action="{{ route('parent.access') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input type="text" 
                           class="form-control form-control-access" 
                           name="parent_code" 
                           placeholder="ORTU-XXXXXXXXXX" 
                           required 
                           autocomplete="off"
                           maxlength="15">
                </div>
                <button type="submit" class="btn btn-access w-100 mb-3">
                    <i class="fas fa-search me-2"></i> Pantau Aktifitas Siswa
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Utama Login
                </a>
            </div>

            <div class="info-box">
                <strong><i class="fas fa-info-circle me-1"></i> Informasi:</strong><br>
                Setiap siswa memiliki kode akses pemantauan unik yang terhubung langsung dengan databasenya (Nilai Tugas, Kehadiran Kelas, & Catatan Perilaku). Jika Anda tidak tahu kodenya, silakan hubungi Wali Kelas.
            </div>
        </div>
    </div>

</body>
</html>
