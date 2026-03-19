@extends('layouts.lms')

@section('title', 'Monitoring')

@section('content')
    <h1 class="h3 mb-3">Monitoring Semua</h1>

    <div class="row">
        @foreach($stats as $label => $value)
            <div class="col-md-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">{{ str_replace('_', ' ', $label) }}</div>
                        <div class="display-6">{{ $value }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

