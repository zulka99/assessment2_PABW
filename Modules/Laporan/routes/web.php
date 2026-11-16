<?php

use Illuminate\Support\Facades\Route;
use Modules\Laporan\Http\Controllers\LaporanController;

Route::middleware(['auth', 'role:petugas,warga'])->group(function () {
    Route::get(
        '/laporan/{laporan}/foto',
        [LaporanController::class, 'foto']
    )->name('laporan.foto');

    Route::resource('laporan', LaporanController::class)
        ->parameters(['laporan' => 'laporan'])
        ->only(['index', 'edit']);

    Route::middleware('role:warga')->group(function () {
        Route::resource('laporan', LaporanController::class)
            ->parameters(['laporan' => 'laporan'])
            ->only(['store', 'update', 'destroy']);
    });

    Route::patch(
        '/laporan/{laporan}/status',
        [LaporanController::class, 'status']
    )->middleware('role:petugas')
        ->name('laporan.status');
});