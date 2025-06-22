<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/level', [LevelController::class, 'index'])->name('level.index');
        Route::post('/level/list', [LevelController::class, 'list'])->name('level.list'); 
        Route::get('/level/create', [LevelController::class, 'create'])->name('level.create');
        Route::post('/level', [LevelController::class, 'store'])->name('level.store');
        Route::get('/level/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');
        Route::put('/level/{id}', [LevelController::class, 'update'])->name('level.update');
        Route::delete('/level/{id}', [LevelController::class, 'destroy'])->name('level.destroy');
    });

    Route::middleware(['authorize:ADM,MNG'])->group(function() {
        Route::get('/barang', [BarangController::class, 'index']);
        Route::post('/barang/list', [BarangController::class, 'list']);
        Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); // ajax form create
        Route::post('/barang_ajax', [BarangController::class, 'store_ajax']); // ajax store
        Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // ajax form edit
        Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']); // ajax update
        Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // ajax form confirm
        Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // ajax delete
        Route::get('/barang/import', [BarangController::class, 'import']); // ajax form upload excel
        Route::post('/barang/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel

        Route::get('/barang/export_excel', [BarangController::class, 'export_excel']);
        Route::get('/barang/export-pdf', [BarangController::class, 'export_pdf']); // ✅ Tambahkan route export PDF
    });

    Route::prefix('user/ajax')->name('user.ajax.')->group(function () {
        Route::get('/create', [UserController::class, 'create_ajax'])->name('create');
        Route::post('/store', [UserController::class, 'store_ajax'])->name('store');
        Route::post('/upload', [UserController::class, 'upload_ajax'])->name('upload');
        Route::get('/{id}/edit', [UserController::class, 'edit_ajax'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update_ajax'])->name('update');
        Route::get('/{id}/confirm-delete',[UserController::class, 'confirm_ajax'])->name('confirm-delete');
        Route::delete('/{id}', [UserController::class, 'delete_ajax'])->name('delete');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/list', [UserController::class, 'list'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

});
