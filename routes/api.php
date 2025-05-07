<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [HomeController::class, 'getProvinces'])->name('provinces');
Route::post('/districts', [HomeController::class, 'getDistricts'])->name('districts');
Route::post('/subdistricts', [HomeController::class, 'getSubDistricts'])->name('subdistricts');
