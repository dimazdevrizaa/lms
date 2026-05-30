@extends('layouts.lms')

@section('title', 'Laporan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Laporan</h1>
        <a class="btn btn-outline-primary" href="{{ route('tatausaha.reports.print') }}" target="_blank">Cetak</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Ringkasan Siswa</div>
                <div class="card-body">
                    <p>Total siswa: <strong>{{ $students->count() }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Ringkasan Guru</div>
                <div class="card-body">
                    <p>Total guru: <strong>{{ $teachers->count() }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection

