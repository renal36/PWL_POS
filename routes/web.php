<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);

Route::get('/kategori', [KategoriController::class, 'index']);

Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);

Route::post('user/tambah_simpan', [UserController::class, 'tambah_simpan']);

// Menampilkan form untuk mengubah data user
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);

// Menyimpan perubahan data user
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);



Route::get('user/hapus/{id}', [UserController::class, 'hapus']);




// Route::get('/kategori/update/{id}', [KategoriController::class, 'update']);
// Route::get('/kategori/delete/{id}', [KategoriController::class, 'delete']);
// Route::get('/kategori/insert', [KategoriController::class, 'insert']);