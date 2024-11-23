<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Middleware\EnsureRoleIsAuthenticated;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\DashboardController;
use App\Http\Controllers\Admin\Auth\AdminOrmawaController;
use App\Http\Controllers\Admin\Auth\AdminDosenController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminDashboardController;
Route::get('/', function () {
    return view('welcome');
});

//login
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');


//ormawa
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


//dosen
Route::prefix('dosen')->middleware(EnsureRoleIsAuthenticated::class . ':dosen')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboardDosen'])->name('dosen.dashboard');
    Route::get('/buat-tanda-tangan', [DosenController::class, 'create'])->name('user.dosen.create');
    Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');
    Route::get('/riwayat', [DosenController::class, 'riwayat'])->name('dosen.riwayat');
});


//admin
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.adminDashboard');
        Route::get('ormawa/index', [AdminOrmawaController::class, 'index'])->name('admin.ormawa.index');
        Route::get('ormawa/create', [AdminOrmawaController::class, 'create'])->name('admin.ormawa.create');
        Route::post('ormawa', [AdminOrmawaController::class, 'store'])->name('admin.ormawa.store');
        Route::get('dosen/index', [AdminDosenController::class, 'index'])->name('admin.dosen.index');
        // Route::get('dosen/create', [AdminDosenController::class, 'create'])->name('admin.dosen.create');
        Route::post('dosen', [AdminDosenController::class, 'store'])->name('admin.dosen.store');
    });
});