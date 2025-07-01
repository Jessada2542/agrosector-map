<?php

use App\Http\Controllers\admin\MapController as AdminMapController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

/* Route::post('/geocode/import', [HomeController::class, 'import'])->name('geocode.import'); */
Route::get('/now', function () {
    return now()->toDateTimeString(); // หรือ Carbon::now()
});

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
            Route::post('/planting/report/data', 'plantingReport')->name('dashboard.planting.report');
            Route::post('/planting/report/create', 'plantingReportCreate')->name('dashboard.planting.report.create');
            Route::get('/data/{id}', 'data')->name('dashboard.data');
        });
    });

    Route::controller(UserController::class)->group(function() {
        Route::prefix('/user')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');

            Route::group(['prefix' => 'planting'], function() {
                Route::match(['get', 'post'], '/', 'planting')->name('user.planting');
                Route::get('/data/{id}', 'plantingData')->name('user.planting.data');
                Route::get('/edit/{id}', 'plantingEdit')->name('user.planting.edit');
                Route::post('/add', 'plantingAdd')->name('user.planting.add');
                Route::put('/update', 'plantingUpdate')->name('user.planting.update');
                Route::put('/off', 'plantingOff')->name('user.planting.off');
            });
        });
    });

    Route::controller(SettingController::class)->group(function() {
        Route::prefix('/setting')->group(function() {
            Route::match(['get', 'post'], '/', 'index')->name('setting.index');
            Route::get('/edit/device/{id}', 'edit')->name('setting.edit.device');
            Route::put('/update/device', 'update')->name('setting.update.device');
        });
    });

    Route::get('/sensor/generate', [SensorController::class, 'generateSensor'])->name('sensor.generate');

    Route::middleware(['check.role'])->prefix('/admin')->group(function () {
        Route::controller(AdminMapController::class)->group(function() {
            Route::get('/map', 'index')->name('admin.index');
            Route::match(['get', 'post'], '/dashboard', 'dashboard')->name('admin.dashboard');
            Route::get('/dashboard/data', 'dashboardData')->name('admin.data');
            Route::match(['get', 'post'], '/users', 'users')->name('admin.users');
            Route::get('/users/data', 'usersData')->name('admin.users.data');
            Route::post('/users/store', 'usersStore')->name('admin.users.store');
            Route::post('/users/update', 'usersUpdate')->name('admin.users.update');
            Route::match(['get', 'post'], '/planting', 'planting')->name('admin.planting');
            Route::get('/planting/data', 'plantingData')->name('admin.planting.data');
        });
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
