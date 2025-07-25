<?php

use App\Models\News;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DireksiController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;

// Homepage - Arahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Route
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $news = News::latest()->take(3)->get();
        return view('dashboard', compact('news'));
    })->name('dashboard');
    Route::resource('news', NewsController::class);
    Route::resource('direksi', DireksiController::class)->except(['show']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/api/news', [NewsController::class, 'json']);
Route::get('/api/direksi', [DireksiController::class, 'json']);
