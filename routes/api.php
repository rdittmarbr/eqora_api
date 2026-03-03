<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('client/v1')->name('api.client.v1.')->group(__DIR__ . '/api/client/v1.php');
Route::prefix('admin/v1')->name('api.admin.v1.')->group(__DIR__ . '/api/admin/v1.php');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
