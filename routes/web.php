<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\MindmapController;
use App\Http\Controllers\KataKunciController;
use App\Http\Controllers\RingkasanController;

Route::get('/', function () {
    return view('welcome');
})->name('beranda');

Route::get('/mindmap/brace', [MindmapController::class, 'brace'])->name('mindmap.brace');
Route::get('/mindmap/bubble', [MindmapController::class, 'bubble'])->name('mindmap.bubble');
Route::get('/mindmap/flow', [MindmapController::class, 'flow'])->name('mindmap.flow');
Route::get('/mindmap/multi', [MindmapController::class, 'multi'])->name('mindmap.multi');
Route::get('/mindmap/spider', [MindmapController::class, 'spider'])->name('mindmap.spider');
Route::get('/mindmap/custom', [MindmapController::class, 'custom'])->name('mindmap.custom');

Route::post('mindmap/generate-summary', [MindmapController::class, 'generateSummary'])->name('mindmap.generateSummary');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/store', [AuthController::class, 'auth'])->name('auth');;
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');;
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register/post', [AuthController::class, 'store'])->name('register.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/saved', [MindmapController::class, 'saved'])->name('mindmap.saved');

    Route::get('/materi', action: [MateriController::class, 'index'])->name('materi');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('materi.store');
    Route::put('/materi/{id}/update', [MateriController::class, 'update'])->name('materi.edit');
    Route::delete('/materi/{id}/destroy', [MateriController::class, 'destroy'])->name('materi.destroy');

    Route::get('/ringkasan', [RingkasanController::class, 'index'])->name('ringkasan');
    Route::get('/profil', [AuthController::class, 'profil'])->name('profil');
    Route::put('/profil/{id}/update-name', [AuthController::class, 'updateName'])->name('update_profile_name');
    Route::put('/profil/update-password', [AuthController::class, 'updatePassword'])->name('update_password');

    Route::post('/mindmap/save', [MindmapController::class, 'save'])->name('mindmap.save');

    Route::patch('/mindmaps/{id}/toggle-share', [MindmapController::class, 'toggleShare'])->name('mindmap.toggle-share');
    Route::get('/mindmaps/{id}/summary', [MindmapController::class, 'summary'])->name('mindmap.summary');
    Route::delete('/mindmaps/{id}', [MindmapController::class, 'destroy'])->name('mindmap.destroy');
    Route::post('/mindmap/{id}/ringkasan', [MindmapController::class, 'simpanRingkasan']);

    Route::get('/mindmap/{id}/ringkasan', [MindmapController::class, 'getRingkasan'])->name('mindmap.get-ringkasan');
    Route::post('/mindmap/{id}/comment', [MindmapController::class, 'storeComment'])->name('mindmap.comment');
    Route::get('/mindmap/{id}/generate-summary', [MindmapController::class, 'generateSummary'])->name('mindmap.generateSummary');

    Route::get('/api/kata-kunci', [KataKunciController::class, 'index']);
    Route::post('/api/kata-kunci', [KataKunciController::class, 'store']);
    Route::put('/api/kata-kunci/{id}', [KataKunciController::class, 'update']);
    Route::delete('/api/kata-kunci/{id}', [KataKunciController::class, 'destroy']);});
