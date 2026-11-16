<?php

namespace Modules\Laporan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\TitikDrainase\Models\TitikDrainase;
use Modules\Laporan\Http\Requests\LaporanRequest;
use Modules\Laporan\Http\Requests\StatusRequest;
use Modules\Laporan\Models\Laporan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $petugas = $request->user()->role === 'petugas';
        $q = trim((string) $request->input('q', ''));

        $items = Laporan::with(['titikdrainase', 'pelapor'])
            ->when(!$petugas, function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('deskripsi', 'like', '%' . $q . '%')
                        ->orWhere('status', 'like', '%' . $q . '%')
                        ->orWhereHas('titikdrainase', function ($facility) use ($q) {
                            $facility->where('nama', 'like', '%' . $q . '%');
                        });
                });
            })
            ->latest('id')
            ->paginate(5)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'laporan::table',
                compact('items', 'petugas')
            );
        }

        $laporan = Laporan::orderBy('nama')->get();

        return view(
            'laporan::index',
            compact('items', 'petugas', 'titikdrainase')
        );
    }

    public function store(LaporanRequest $request)
    {
        $data = $request->safe()->except(['foto']);

        // Identitas pelapor diambil dari sesi login.
        $data['user_id'] = $request->user()->id;
        $data['titik_drainase_id'] = $request->user()->id;
        $data['tinggi_air_cm'] = $request->user()->id;
        $data['status_kondisi'] =$request->user()->id;
        $data['status'] = 'Baru';

        $path = null;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')
                ->store('pengaduan', 'local');

            $data['foto'] = $path;
        }

        try {
            Laporan::create($data);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }

            throw $e;
        }

        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
        ], 201);
    }

    public function edit(Laporan $laporan)
    {
        if (auth()->user()->role !== 'petugas') {
            $this->pastikanBolehDiubah($laporan);
        }

        return response()->json($laporan->only([
            'id',
            'user_id',
            'titik_drainase_id',
            'tinggi_air_cm',
            'status_kondisi',
        ]));
    }

    public function update(
        LaporanRequest $request,
        Laporan $laporan
    ) {
        $data = $request->safe()->except(['foto']);
        $fotoLama = $laporan->foto;
        $fotoBaru = null;

        if ($request->hasFile('foto')) {
            $fotoBaru = $request->file('foto')
                ->store('laporan', 'local');

            $data['foto'] = $fotoBaru;
        }

        try {
            $laporan->update($data);
        } catch (\Throwable $e) {
            if ($fotoBaru) {
                Storage::disk('local')->delete($fotoBaru);
            }

            throw $e;
        }

        if ($fotoBaru && $fotoLama) {
            Storage::disk('local')->delete($fotoLama);
        }

        return response()->json([
            'message' => 'Laporan berhasil diperbarui.',
        ]);
    }

    public function destroy(Laporan $laporan)
    {
        $this->pastikanBolehDiubah($laporan);

        $foto = $laporan->foto;
        $laporan->delete();

        if ($foto) {
            Storage::disk('local')->delete($foto);
        }

        return response()->json([
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }

    public function status(
        StatusRequest $request,
        Laporan $laporan
    ) {
        $laporan->update($request->validated());

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui.',
        ]);
    }

    public function foto(Laporan $laporan)
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'petugas'
            || (int) $laporan->user_id === (int) $user->id,
            403
        );

        abort_unless(
            $laporan->foto
            && Storage::disk('local')->exists($laporan->foto),
            404
        );

        return Storage::disk('local')->response($laporan->foto);
    }

    private function pastikanBolehDiubah(Laporan $laporan): void
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'warga'
            && (int) $laporan->user_id === (int) $user->id
            && $laporan->status === 'Baru',
            403
        );
    }
}