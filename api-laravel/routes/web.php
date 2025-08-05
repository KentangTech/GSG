<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\DireksiController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('direksi', DireksiController::class);
    Route::resource('bisnis', BisnisController::class)->parameters(['bisnis' => 'bisnis']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Api Publik

Route::get('api/direksi', [DireksiController::class, 'json']);
Route::get('api/bisnis', [BisnisController::class, 'json']);
