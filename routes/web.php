<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\DosenController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');

Route::prefix('ormawa')->middleware('auth:ormawa')->group(function () {
    Route::get('/dashboard', [OrmawaController::class, 'dashboard'])->name('ormawa.dashboard');
    Route::get('/pengajuan', [OrmawaController::class, 'pengajuan'])->name('ormawa.pengajuan');
    Route::post('/pengajuan', [OrmawaController::class, 'storePengajuan'])->name('ormawa.pengajuan.store');
    Route::get('/riwayat', [OrmawaController::class, 'riwayat'])->name('ormawa.riwayat');
    // rute ormawa lainnya
});

Route::prefix('dosen')->middleware('auth:dosen')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboardDosen'])->name('dosen.dashboard');
    // rute dosen lainnya
});

Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');