@extends('layouts.lms')

@section('title', 'Monitoring')

@section('content')
    <!-- Header Banner -->
    <div class="content-card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--secondary) 100%); border: none; margin-bottom: 32px;">
        <div class="content-card-body" style="padding: 32px;">
            <h1 class="mb-1" style="color: #FFFFFF !important; font-size: 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                📊 Monitoring Sistem
            </h1>
            <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 0.9rem;">
                Pantau statistik keseluruhan sistem LMS
            </p>
        </div>
    </div>

    <div class="stats-grid">
        @foreach($stats as $label => $value)
            @php
                $icons = ['total_users' => '👥', 'total_guru' => '🎓', 'total_siswa' => '🎒', 'total_kelas' => '🏫', 'total_mapel' => '📚'];
                $variants = ['total_users' => 'gold', 'total_guru' => 'green', 'total_siswa' => 'deep', 'total_kelas' => 'green', 'total_mapel' => 'gold'];
                $cardVariants = ['total_users' => 'grades', 'total_guru' => 'attendance', 'total_siswa' => 'behavior', 'total_kelas' => 'attendance', 'total_mapel' => 'grades'];
                $icon = $icons[$label] ?? '📊';
                $variant = $variants[$label] ?? 'green';
                $cardVariant = $cardVariants[$label] ?? 'attendance';
            @endphp
            <div class="stat-card stat-card--{{ $cardVariant }}">
                <div class="d-flex align-items-start gap-3">
                    <div class="stat-icon-circle stat-icon-circle--{{ $variant }}">{{ $icon }}</div>
                    <div>
                        <div class="stat-label">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
                        <div class="stat-value stat-value--{{ $variant === 'deep' ? 'primary' : 'green' }}">{{ $value }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
