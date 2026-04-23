@extends('layouts.lms')

@section('title', 'Edit Tahun Ajaran')

@section('content')
    <h1 class="h3 mb-3">Edit Tahun Ajaran</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.academic-years.update', $year) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="name" value="{{ old('name', $year->name) }}" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai (Opsional)</label>
                        <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $year->start_date ? $year->start_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai (Opsional)</label>
                        <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $year->end_date ? $year->end_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active"
                           @checked(old('is_active', $year->is_active))>
                    <label class="form-check-label" for="is_active">Jadikan aktif</label>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.academic-years.index') }}">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection

