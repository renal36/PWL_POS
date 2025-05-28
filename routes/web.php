<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Halaman awal
Route::get('/', [WelcomeController::class, 'index']);

// Group route dengan prefix 'user'
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/list', [UserController::class, 'list'])->name('user.list');

    // CRUD biasa (non-AJAX)
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/edit', [UserController::class, 'edit']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);

    // Grup AJAX
   Route::prefix('ajax')->group(function () {
    Route::get('/create', [UserController::class, 'create_ajax']);
    Route::post('/user', [UserController::class, 'store_ajax']);      // POST user/ajax/user
    Route::post('/upload', [UserController::class, 'upload_ajax']);
    Route::get('/{id}/edit', [UserController::class, 'edit_ajax']);
    Route::put('/{id}', [UserController::class, 'update_ajax']);
    Route::get('/{id}/delete', [UserController::class, 'confirm_ajax']);
    Route::delete('/{id}', [UserController::class, 'delete_ajax']);
});

});


// Route kategori (komentar, belum aktif)
// Route::get('/kategori/update/{id}', [KategoriController::class, 'update']);
// Route::get('/kategori/delete/{id}', [KategoriController::class, 'delete']);
// Route::get('/kategori/insert', [KategoriController::class, 'insert']);

