<?php

use Illuminate\Support\Facades\Route;
use Modules\TitikDrainase\Http\Controllers\TitikDrainaseController;

Route::middleware(['auth', 'role:petugas,warga'])->group(function () {
    Route::resource('titikdrainase', TitikDrainaseController::class)
        ->parameters(['titikdrainase' => 'titikdrainase'])
        ->only(['index']);

    Route::middleware('role:petugas')->group(function () {
        Route::resource('titikdrainase', TitikDrainaseController::class)
            ->parameters(['titikdrainase' => 'titikdrainase'])
            ->only([
                'create',
                'store',
                'edit',
                'update',
                'destroy',
            ]);
    });
});