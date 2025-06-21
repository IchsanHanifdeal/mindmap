<?php

use App\Http\Controllers\MindmapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('beranda');

Route::get('/mindmap', [MindmapController::class, 'index'])->name('mindmap');
Route::post('mindmap/generate-summary', [MindmapController::class, 'generateSummary'])->name('mindmap.generateSummary');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/store', [AuthController::class, 'auth'])->name('auth');;
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register/post', [AuthController::class, 'store'])->name('register.store');
