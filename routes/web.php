<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\TitikDrainase\Models\TitikDrainase;
use Modules\Laporan\Models\Laporan;

Route::get('/', fn () => redirect('/dashboard'));

Auth::routes();

Route::middleware(['auth', 'role:petugas,warga'])->group(function () {
    Route::get('/home', fn () => redirect('/dashboard'))
        ->name('home');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        $laporan = Laporan::query()
            ->when($user->role === 'warga', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        $totalTitikDrainase = TitikDrainase::count();
        $totalLaporan = (clone $laporan)->count();
        $totalSelesai = (clone $laporan)
            ->where('status', 'Selesai')
            ->count();

        return view('dashboard', compact(
            'totalTitikDrainase',
            'totalLaporan',
            'totalSelesai'
        ));
    })->name('dashboard');
});