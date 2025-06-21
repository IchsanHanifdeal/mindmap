<?php

use App\Http\Controllers\MindmapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mindmap', [MindmapController::class, 'index'])->name('mindmap');
