<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Titik Drainase / Pelapor</th>
                <th>Laporan</th>
                <th>Foto</th>
                <th>Status / Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->titikdrainase->nama }}</strong><br>
                        {{ $item->pelapor->name }}
                    </td>

                    <td style="min-width:200px">
                        {{ $item->deskripsi }}
                    </td>

                    <td>
                        @if($item->foto)
                            <a href="{{ route('laporan.foto', $item) }}"
                               target="_blank"
                               rel="noopener">
                                <img src="{{ route('laporan.foto', $item) }}"
                                     alt="Foto {{ $item->titikdrainase->nama }}"
                                     width="80"
                                     height="70"
                                     style="object-fit:cover"
                                     class="rounded">
                            </a>
                        @else
                            Tidak ada foto
                        @endif
                    </td>

                    <td style="min-width:170px">
                        <span class="badge text-bg-secondary">
                            {{ $item->status }}
                        </span>

                        <div class="small mt-2">
                            {{ $item->catatan ?: 'Belum ada catatan.' }}
                        </div>
                    </td>

                    <td class="text-nowrap">
                        @if($petugas)
                            <button type="button"
                                    class="btn btn-sm btn-primary edit"
                                    data-url="{{ route('laporan.edit', $item) }}">
                                Tangani
                            </button>
                        @elseif($item->status === 'Baru')
                            <button type="button"
                                    class="btn btn-sm btn-warning edit"
                                    data-url="{{ route('laporan.edit', $item) }}">
                                Edit
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-danger delete"
                                    data-url="{{ route('laporan.destroy', $item) }}">
                                Hapus
                            </button>
                        @else
                            <span class="small text-body-secondary">
                                Tidak dapat diubah
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada pengaduan yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $items->links() }}