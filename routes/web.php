<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Welcome)
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard (Hanya bisa diakses jika sudah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Group Middleware Auth (Profile & Chat)
Route::middleware('auth')->group(function () {
    
    // Rute untuk Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [ChatController::class, 'sendMessage'])->name('chat.send');
    
});

// 4. Memuat sistem login & daftar bawaan (Breeze/Jetstream)
require __DIR__.'/auth.php';
