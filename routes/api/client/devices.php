<?php

use App\Http\Controllers\Client\V1\DeviceController;
use Illuminate\Support\Facades\Route;

// Legacy alias while clients migrate from "devices" to "terminals".
Route::prefix('devices')->name('devices.')->group(function () {
    Route::get('/', [DeviceController::class, 'index'])
        ->name('index');

    Route::post('/', [DeviceController::class, 'store'])
        ->name('store');

    Route::get('/{device}', [DeviceController::class, 'show'])
        ->name('show');
});
