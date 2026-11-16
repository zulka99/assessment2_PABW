@extends('layouts.app')

@section('content')
<div id="laporan-page"
     data-base="{{ url('/laporan') }}"
     data-petugas="{{ $petugas ? '1' : '0' }}">

    <h1 class="h3">
        {{ $petugas ? 'Laporan Warga' : 'Laporan Saya' }}
    </h1>

    <div id="notice" role="status" aria-live="polite"></div>

    <form id="form-laporan"
          method="POST"
          action="{{ url('/laporan') }}"
          enctype="multipart/form-data"
          class="card card-body mb-4"
          novalidate>
        @csrf

        <input type="hidden" id="record-id">

        <h2 class="h5" id="form-title">
            {{ $petugas ? 'Pilih laporan melalui tombol Tangani' : 'Buat laporan' }}
        </h2>

        @if($petugas)
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>

                <select name="status" id="status" class="form-select">
                    <option value="Baru">Baru</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="catatan" class="form-label">
                    Catatan penanganan
                </label>

                <textarea name="catatan"
                          id="catatan"
                          rows="3"
                          class="form-control"></textarea>
            </div>
        @else
            @if($fasilitas->isEmpty())
                <p class="text-warning">
                    Belum ada titikdrainase. Petugas perlu menambahkannya dahulu.
                </p>
            @endif

            <div class="mb-3">
                <label for="titik_drainase_id" class="form-label">
                    Titik Drainase
                </label>

                <select name="titikdrainase_id"
                        id="titikdrainase_id"
                        class="form-select">
                    <option value="">Pilih Titik Drainase</option>

                    @foreach($titikdrainase as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }} - {{ $item->kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">
                    Deskripsi
                </label>

                <textarea name="deskripsi"
                          id="deskripsi"
                          rows="3"
                          class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">
                    Foto, opsional
                </label>

                <input type="file"
                       name="foto"
                       id="foto"
                       accept="image/jpeg,image/png,image/webp"
                       class="form-control">

                <div class="form-text">
                    Maksimal 2 MB. Kosongkan saat edit untuk mempertahankan foto.
                </div>
            </div>
        @endif

        <div class="d-flex gap-2">
            <button id="save"
                    type="submit"
                    class="btn btn-primary"
                    @disabled($petugas)>
                Simpan
            </button>

            <button id="cancel"
                    type="button"
                    class="btn btn-secondary">
                Batal
            </button>
        </div>
    </form>

    <form id="search-form" class="d-flex gap-2 mb-3">
        <input name="q"
               value="{{ request('q') }}"
               class="form-control"
               placeholder="Cari titik drainase, deskripsi, atau status"
               aria-label="Cari laporan">

        <button class="btn btn-outline-primary">Cari</button>
    </form>

    <p id="loading" class="d-none" role="status">Memuat data...</p>

    <div id="table-area">
        @include('laporan::table')
    </div>
</div>
@endsection