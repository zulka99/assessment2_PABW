@extends('layouts.app')

@section('content')
    <h1 class="h3">
        Dashboard {{ ucfirst(auth()->user()->role) }}
    </h1>

    <p>Halo, {{ auth()->user()->name }}.</p>

    @if(auth()->user()->role === 'petugas')
        <p>Kelola titik drainase dan tangani laporan warga.</p>
    @else
        <p>Laporkan kerusakan dan pantau laporanmu.</p>
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card card-body">
                <h2 class="h6">Total Titik Drainase</h2>
                <p class="display-6 mb-0">
                    {{ $totalTitikDrainase }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-body">
                <h2 class="h6">
                    {{ auth()->user()->role === 'petugas'
                        ? 'Seluruh Laporan'
                        : 'Laporan Saya' }}
                </h2>

                <p class="display-6 mb-0">
                    {{ $totalLaporan }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-body">
                <h2 class="h6">Laporan Selesai</h2>
                <p class="display-6 mb-0">
                    {{ $totalSelesai }}
                </p>
            </div>
        </div>
    </div>
@endsection