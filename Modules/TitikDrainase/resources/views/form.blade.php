@extends('layouts.app')

@section('content')
    <h1 class="h3">
        {{ $fasilitas->exists ? 'Edit Titik Drainase' : 'Tambah Titik Drainase' }}
    </h1>

    <form method="POST"
          action="{{ $fasilitas->exists
              ? route('titikdrainase.update', $fasilitas)
              : route('titikdrainase.store') }}"
          class="card card-body"
          novalidate>
        @csrf

        @if($fasilitas->exists)
            @method('PUT')
        @endif

        @foreach([
            'nama_titik' => 'Nama Titik Drainase',
            'kecamatan' => 'Kecamatan',
            'deskripsi' => 'Deskripsi'
            'kedalaman_cm' => 'Kedalaman cm'
        ] as $field => $label)
            <div class="mb-3">
                <label for="{{ $field }}" class="form-label">
                    {{ $label }}
                </label>

                <input id="{{ $field }}"
                       name="{{ $field }}"
                       value="{{ old($field, $titikdrainase->$field) }}"
                       class="form-control @error($field) is-invalid @enderror">

                @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="mb-3">
            <label for="kondisi" class="form-label">Kondisi</label>

            <select id="kondisi"
                    name="kondisi"
                    class="form-select @error('kondisi') is-invalid @enderror">
                <option value="">Pilih kondisi</option>

                @foreach(['Baik', 'Rusak', 'Perbaikan'] as $kondisi)
                    <option value="{{ $kondisi }}"
                        @selected(old('kondisi', $titikdrainase->kondisi) === $kondisi)>
                        {{ $kondisi }}
                    </option>
                @endforeach
            </select>

            @error('kondisi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>

            <a href="{{ route('titikdrainase.index') }}"
               class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
@endsection