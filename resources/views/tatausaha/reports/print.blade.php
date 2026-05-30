<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h1 class="h4 mb-3">Laporan - LMS SMA 15 Padang</h1>

<h2 class="h5 mt-4">Data Siswa</h2>
<table class="table table-bordered table-sm">
    <thead>
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->nis }}</td>
            <td>{{ $student->user?->name ?? '-' }}</td>
            <td>{{ $student->schoolClass?->name ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h2 class="h5 mt-4">Data Guru</h2>
<table class="table table-bordered table-sm">
    <thead>
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>NIP</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teachers as $teacher)
        <tr>
            <td>{{ $teacher->user?->name ?? '-' }}</td>
            <td>{{ $teacher->user?->email ?? '-' }}</td>
            <td>{{ $teacher->nip ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    window.print();
</script>
</body>
</html>

