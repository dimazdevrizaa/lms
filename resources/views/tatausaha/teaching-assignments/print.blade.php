<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Penugasan Guru Mengajar {{ $academicYear ? '- ' . $academicYear->name : '' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #1a1a1a;
            font-size: 11pt;
            line-height: 1.5;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 30px;
        }

        /* Header Sekolah */
        .school-header {
            text-align: center;
            border-bottom: 3px double #25671E;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .school-header h2 {
            font-size: 16pt;
            color: #25671E;
            margin-bottom: 2px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .school-header h3 {
            font-size: 11pt;
            color: #444;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .school-header .sub-info {
            font-size: 9pt;
            color: #888;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .doc-title h1 {
            font-size: 14pt;
            font-weight: 700;
            color: #25671E;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .doc-title .academic-year {
            font-size: 10pt;
            color: #666;
            font-weight: 500;
        }

        /* Class Section */
        .class-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .class-section-header {
            background: linear-gradient(135deg, #25671E, #48A111);
            color: white;
            padding: 10px 16px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .class-section-header h4 {
            font-size: 11pt;
            margin: 0;
            font-weight: 700;
        }

        .class-section-header .major-badge {
            background: rgba(255,255,255,0.25);
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 8pt;
            font-weight: 600;
        }

        .class-section-header .count-badge {
            margin-left: auto;
            font-size: 8pt;
            opacity: 0.85;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        table thead th {
            background: #f5f5f5;
            color: #333;
            font-weight: 600;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tbody td {
            padding: 7px 12px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }

        table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .no-col {
            text-align: center;
            width: 40px;
            color: #888;
        }

        /* Footer & TTD */
        .print-footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .ttd-section {
            text-align: center;
            min-width: 250px;
        }

        .ttd-section .place-date {
            font-size: 10pt;
            margin-bottom: 4px;
        }

        .ttd-section .role {
            font-size: 10pt;
            font-weight: 600;
            margin-bottom: 60px;
        }

        .ttd-section .name-line {
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 4px;
            min-width: 180px;
            font-size: 10pt;
            font-weight: 600;
        }

        .ttd-section .nip {
            font-size: 9pt;
            color: #666;
        }

        /* Toolbar (non-print) */
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #25671E, #48A111);
            padding: 12px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .print-toolbar .toolbar-title {
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .print-toolbar .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .print-toolbar button, .print-toolbar a {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-print {
            background: white;
            color: #25671E;
        }

        .btn-print:hover {
            background: #f0f0f0;
            transform: translateY(-1px);
        }

        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3) !important;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.3);
        }

        .toolbar-spacer {
            height: 60px;
        }

        /* Print styles */
        @media print {
            .print-toolbar,
            .toolbar-spacer {
                display: none !important;
            }

            body {
                font-size: 10pt;
            }

            .print-container {
                padding: 0;
                max-width: 100%;
            }

            .class-section {
                page-break-inside: avoid;
            }

            .class-section-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }

            table thead th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }

            table tbody tr:nth-child(even) {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Non-print toolbar -->
    <div class="print-toolbar">
        <span class="toolbar-title">
            <i>📋</i> Preview Cetak — Penugasan Guru {{ $academicYear ? '(' . $academicYear->name . ')' : '' }}
        </span>
        <div class="toolbar-actions">
            <a href="{{ route('tatausaha.teaching-assignments.index') }}" class="btn-back">
                ← Kembali
            </a>
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak / Save PDF
            </button>
        </div>
    </div>
    <div class="toolbar-spacer"></div>

    <div class="print-container">
        <!-- Header Sekolah -->
        <div class="school-header">
            <h3>PEMERINTAH PROVINSI SUMATERA BARAT</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h2>SMA NEGERI 15 PADANG</h2>
            <div class="sub-info">Jl. Limau Manis, Pauh, Kota Padang, Sumatera Barat</div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            <h1>Penugasan Guru Mengajar</h1>
            @if($academicYear)
                <div class="academic-year">Tahun Pelajaran: {{ $academicYear->name }}</div>
            @endif
        </div>

        <!-- Tabel per Mata Pelajaran -->
        @forelse($orderedGroups as $subjectName => $group)
            @php
                $assignments = $group['assignments'];
                $firstSubject = $assignments->first()?->subject;
            @endphp
            <div class="class-section">
                <div class="class-section-header">
                    <h4>Mata Pelajaran: {{ $subjectName }}</h4>
                    @if($firstSubject && $firstSubject->major)
                        <span class="major-badge">{{ $firstSubject->major }}</span>
                        @if($firstSubject->code)
                            <span class="major-badge">{{ $firstSubject->code }}</span>
                        @endif
                    @endif
                    <span class="count-badge">{{ $assignments->count() }} Kelas</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="no-col">No</th>
                            <th>Kelas</th>
                            <th>Tingkat</th>
                            <th>Guru Pengampu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $a)
                            <tr>
                                <td class="no-col">{{ $loop->iteration }}</td>
                                <td>{{ $a->schoolClass->name ?? '-' }}</td>
                                <td>{{ $a->schoolClass->level ?? '-' }}</td>
                                <td>{{ $a->teacher->user->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #999;">
                <p>Tidak ada data penugasan untuk ditampilkan.</p>
            </div>
        @endforelse

        <!-- Footer TTD -->
        <div class="print-footer">
            <div class="ttd-section">
                <div class="place-date">Padang, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="role">Kepala Tata Usaha</div>
                <div class="name-line">&nbsp;</div>
                <div class="nip">NIP. ___________________</div>
            </div>
        </div>
    </div>
</body>
</html>
