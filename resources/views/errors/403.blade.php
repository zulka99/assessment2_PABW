@extends('layouts.app')

@section('content')
    <h1>403 - Akses ditolak</h1>
    <p>Akun kamu tidak memiliki izin untuk tindakan ini.</p>

    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
        Kembali
    </a>
@endsection