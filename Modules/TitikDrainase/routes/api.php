<?php

use Illuminate\Support\Facades\Route;
use Modules\TitikDrainase\Http\Controllers\TitikDrainaseController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('titikdrainases', TitikDrainaseController::class)->names('titikdrainase');
});
