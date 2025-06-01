<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;


// Route untuk halaman utama
Route::get('/', [WelcomeController::class, 'index']);

// Group route untuk AJAX
Route::prefix('user/ajax')->group(function () {
    // Menampilkan form untuk membuat user baru (via AJAX/modal)
    Route::get('/create', [UserController::class, 'create_ajax'])->name('user.ajax.create');

    // Menyimpan data user baru (via AJAX)
    Route::post('/store', [UserController::class, 'store_ajax'])->name('user.ajax.store');

    // Mengupload file (misalnya untuk foto profil, via AJAX)
    Route::post('/upload', [UserController::class, 'upload_ajax'])->name('user.ajax.upload');

    // Menampilkan form edit user berdasarkan ID (via AJAX/modal)
    Route::get('/{id}/edit', [UserController::class, 'edit_ajax'])->name('user.ajax.edit');

    // Memperbarui data user berdasarkan ID (via AJAX)
    // Gunakan PUT/PATCH untuk update, sesuai RESTful convention
    Route::put('/{id}', [UserController::class, 'update_ajax'])->name('user.ajax.update');

    // Menampilkan konfirmasi hapus user berdasarkan ID (via AJAX/modal)
    Route::get('/{id}/confirm-delete', [UserController::class, 'confirm_ajax'])->name('user.ajax.confirm-delete');
    // Mengubah rute confirm delete menjadi lebih deskriptif, misal: /user/ajax/{id}/confirm-delete

    // Menghapus data user berdasarkan ID (via AJAX)
    // Gunakan DELETE sesuai RESTful convention
    Route::delete('/{id}', [UserController::class, 'delete_ajax'])->name('user.ajax.delete');
});

// Group route untuk CRUD user biasa
Route::prefix('user')->group(function () {
    // Menampilkan daftar user
    Route::get('/', [UserController::class, 'index'])->name('user.index'); // Tambahkan nama rute

    // Endpoint untuk DataTables (mengambil data list user)
    Route::get('/list', [UserController::class, 'list'])->name('user.list');

    // Menampilkan form untuk membuat user baru (non-AJAX)
    Route::get('/create', [UserController::class, 'create'])->name('user.create');

    // Menyimpan data user baru (non-AJAX)
    Route::post('/', [UserController::class, 'store'])->name('user.store');

    // Menampilkan detail user berdasarkan ID
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');

    // Menampilkan form edit user berdasarkan ID
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');

    // Memperbarui data user berdasarkan ID
    Route::put('/{id}', [UserController::class, 'update'])->name('user.update');

    // Menghapus data user berdasarkan ID
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});