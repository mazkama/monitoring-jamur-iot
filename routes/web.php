<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\ThresholdController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api', [DashboardController::class, 'apiData'])->name('dashboard.api');

    // Devices
    Route::resource('devices', DeviceController::class);
    Route::post('devices/{device}/toggle', [DeviceController::class, 'toggleStatus'])->name('devices.toggle');

    // Harvests
    Route::resource('harvests', HarvestController::class);

    // Thresholds
    Route::get('thresholds', [ThresholdController::class, 'index'])->name('thresholds.index');
    Route::post('thresholds', [ThresholdController::class, 'store'])->name('thresholds.store');
    Route::put('thresholds/{threshold}', [ThresholdController::class, 'update'])->name('thresholds.update');
    Route::delete('thresholds/{threshold}', [ThresholdController::class, 'destroy'])->name('thresholds.destroy');

    // Alerts
    Route::get('alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('alerts/{alert}/resolve', [AlertController::class, 'resolve'])->name('alerts.resolve');

    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
