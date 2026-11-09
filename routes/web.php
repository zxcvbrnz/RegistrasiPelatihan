<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PesertaController::class, 'create'])->name('peserta.create');
Route::post('/peserta', [PesertaController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('peserta.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('admin.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/data-peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::delete('/data-peserta/{peserta}', [PesertaController::class, 'destroy'])->name('peserta.destroy');
    Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');
});
