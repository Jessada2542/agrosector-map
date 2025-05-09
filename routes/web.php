<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::controller(UserController::class)->group(function() {
        Route::prefix('/user')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
        });
    });

    Route::controller(SettingController::class)->group(function() {
        Route::prefix('/setting')->group(function() {
            Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        });
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
