<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SensorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [HomeController::class, 'getProvinces'])->name('provinces');
Route::post('/districts', [HomeController::class, 'getDistricts'])->name('districts');
Route::post('/subdistricts', [HomeController::class, 'getSubDistricts'])->name('subdistricts');

Route::post('/sensor/marker', [SensorController::class, 'marker'])->name('sensor.marker');
Route::get('/sensor/data/{id}', [SensorController::class, 'data'])->name('sensor.data');

Route::post('/sensor/test', [SensorController::class, 'test'])->name('sensor.test');
Route::post('/sensor/store', [SensorController::class, 'store'])->name('sensor.store');
Route::post('/board/update/connect', [SensorController::class, 'updateConnect'])->name('board.update.connect');

Route::post('/generate/user', [UserController::class, 'genUser'])->name('gen.user');
Route::get('/sensor/generate', [SensorController::class, 'generateSensor'])->name('sensor.generate');


Route::get('/sensor/isilk', [SensorController::class, 'isilk'])->name('sensor.data.isilk');
