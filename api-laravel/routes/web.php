<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\DireksiController;

Route::redirect('/', '/login');

// Guest Routes (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/direksi', function () {
        return view('direksi.index');
    })->name('direksi.index');
});

Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('direksi', [DireksiController::class , 'json']);
});