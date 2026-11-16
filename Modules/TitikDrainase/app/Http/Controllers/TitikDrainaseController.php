<?php

namespace Modules\TitikDrainase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TitikDrainase\Http\Requests\TitikDrainaseRequest;
use Modules\TitikDrainase\app\Models\TitikDrainase;

class TitikDrainaseController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $items = TitikDrainase::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', '%' . $q . '%')
                        ->orWhere('lokasi', 'like', '%' . $q . '%');
                });
            })
            ->latest('id')
            ->paginate(5)
            ->withQueryString();

        return view('titikdrainase::index', compact('items'));
    }

    public function create()
    {
        $titikdrainase = new TitikDrainase();

        return view('titikdrainase::form', compact('titikdrainase'));
    }

    public function store(TitikDrainaseRequest $request)
    {
        TitikDrainase::create($request->validated());

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Titik Drainase berhasil ditambahkan.');
    }

    public function edit(TitikDrainase $titikdrainase)
    {
        return view('titikdrainase::form', compact('titikdrainase'));
    }

    public function update(
        TitikDrainaseRequest $request,
        TitikDrainase $titikdrainase
    ) {
        $titikdrainase->update($request->validated());

        return redirect()
            ->route('titikdrainase.index')
            ->with('success', 'TitikDrainase berhasil diperbarui.');
    }

    public function destroy(TitikDrainase $titikdrainase)
    {
        if ($titikdrainase->laporans()->exists()) {
            return back()->with(
                'error',
                'TitikDrainase memiliki laporan sehingga tidak dapat dihapus.'
            );
        }

        $titikdrainase->delete();

        return back()->with(
            'success',
            'TitikDrainase berhasil dihapus.'
        );
    }
}