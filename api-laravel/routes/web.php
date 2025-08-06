<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\MedsosController;
use App\Http\Controllers\DireksiController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/mark-all-read', [DashboardController::class, 'markAllAsRead'])->name('dashboard.markAllAsRead');

    Route::resource('direksi', DireksiController::class);
    Route::resource('bisnis', BisnisController::class)->parameters(['bisnis' => 'bisnis']);
    Route::resource('news', NewsController::class);

    // Medsos
    Route::get('/medsos', [MedsosController::class, 'index'])->name('medsos.index');
    Route::get('/create', [MedsosController::class, 'create'])->name('medsos.create');
    Route::post('/', [MedsosController::class, 'store'])->name('medsos.store');
    Route::get('/{medsos}/edit', [MedsosController::class, 'edit'])->name('medsos.edit');
    Route::put('/{medsos}', [MedsosController::class, 'update'])->name('medsos.update');
    Route::delete('/{medsos}', [MedsosController::class, 'destroy'])->name('medsos.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Api Publik

Route::get('api/direksi', [DireksiController::class, 'json']);
Route::get('api/bisnis', [BisnisController::class, 'json']);
Route::get('api/news', [NewsController::class, 'json'])->name('news.json');
Route::get('/api/social-media', [MedsosController::class, 'json'])->name('medsos.json');
