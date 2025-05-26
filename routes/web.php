<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

/* Route::post('/geocode/import', [HomeController::class, 'import'])->name('geocode.import'); */

Route::middleware('loggedin')->group(function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [AuthController::class, 'loginView'])->name('login-view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function() {
    Route::get('/map', [MapController::class, 'index'])->name('map.index');

    Route::controller(DashboardController::class)->group(function() {
        Route::prefix('/dashboard')->group(function() {
            Route::match(['get', 'post'], '/', 'index')->name('dashboard.index');
        });
    });

    Route::controller(UserController::class)->group(function() {
        Route::prefix('/user')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::match(['get', 'post'], '/planting', 'planting')->name('user.planting');
        });
    });

    Route::controller(SettingController::class)->group(function() {
        Route::prefix('/setting')->group(function() {
            Route::match(['get', 'post'], '/', 'index')->name('setting.index');
        });
    });

    Route::get('/sensor/generate', [SensorController::class, 'generateSensor'])->name('sensor.generate');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
