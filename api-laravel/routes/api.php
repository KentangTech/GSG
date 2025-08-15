<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\MedsosController;
use App\Http\Controllers\DireksiController;


Route::get('api/direksi', [DireksiController::class, 'json']);
Route::get('api/bisnis', [BisnisController::class, 'json']);
Route::get('api/news', [NewsController::class, 'json'])->name('news.json');
Route::get('api/social-media', [MedsosController::class, 'json'])->name('medsos.json');
