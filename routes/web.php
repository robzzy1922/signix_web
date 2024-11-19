<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\DosenController; 
use App\Http\Middleware\EnsureRoleIsAuthenticated;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');

Route::prefix('ormawa')->middleware(EnsureRoleIsAuthenticated::class . ':ormawa')->group(function () {
    Route::get('/dashboard', [OrmawaController::class, 'dashboard'])->name('ormawa.dashboard');
    Route::get('/pengajuan', [OrmawaController::class, 'pengajuan'])->name('ormawa.pengajuan');
    Route::post('/pengajuan', [OrmawaController::class, 'storePengajuan'])->name('ormawa.pengajuan.store');
    Route::get('/riwayat', [OrmawaController::class, 'riwayat'])->name('ormawa.riwayat');
    Route::get('/dokumen/{id}', [OrmawaController::class, 'getDokumenContent'])->name('dokumen.content');
    Route::get('/profile', [OrmawaController::class, 'showProfile'])->name('ormawa.profile');
    Route::put('/profile/photo/update', [OrmawaController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo/remove', [OrmawaController::class, 'removeProfilePhoto'])->name('profile.photo.remove');
    Route::get('/profile/photo/view', [OrmawaController::class, 'viewPhoto'])->name('profile.photo.view');
    Route::put('/ormawa/profile/photo', [OrmawaController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::put('/profile/update', [OrmawaController::class, 'updateProfile'])->name('profile.update');
});

Route::prefix('dosen')->middleware(EnsureRoleIsAuthenticated::class . ':dosen')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboardDosen'])->name('dosen.dashboard');
    Route::get('/buat-tanda-tangan', [DosenController::class, 'create'])->name('user.dosen.create');
    Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');
    Route::get('/riwayat', [DosenController::class, 'riwayat'])->name('dosen.riwayat');
});


