@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Titik Drainase</h1>

        @if(auth()->user()->role === 'petugas')
            <a href="{{ route('titikdrainase.create') }}"
               class="btn btn-primary">
                Tambah
            </a>
        @endif
    </div>

    <form method="GET"
          action="{{ route('titikdrainase.index') }}"
          class="d-flex gap-2 mb-3">
        <input name="q"
               value="{{ request('q') }}"
               class="form-control"
               placeholder="Cari nama atau kecamatan"
               aria-label="Cari titik drainase">

        <button class="btn btn-outline-primary">Cari</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kecamatan</th>
                    <th>Deskripsi</th>
                    <th>Kedalaman cm</th>

                    @if(auth()->user()->role === 'petugas')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->nama_titik }}</td>
                        <td>{{ $item->kecamatan }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ $item->kedalaman_cm }}</td>

                        @if(auth()->user()->role === 'petugas')
                            <td class="text-nowrap">
                                <a href="{{ route('titikdrainase.edit', $item) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('titikdrainase.destroy', $item) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus titik drainase ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada titik drainase yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $items->links() }}
@endsection