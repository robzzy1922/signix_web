<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrmawaAuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\DosenAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/ormawa/login', [OrmawaAuthController::class, 'login']);
Route::post('/dosen/login', [DosenAuthController::class, 'login']);

// Route untuk mengambil data dosen (tanpa autentikasi)
Route::get('/dosen', [DosenController::class, 'index']);
Route::get('/dosen/{id}/documents', [DosenController::class, 'getDocumentsForDosen']);
Route::get('/dokumen/{id}', [DosenController::class, 'getDokumenDetail']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // Ormawa routes
    Route::post('/ormawa/logout', [OrmawaAuthController::class, 'logout']);
    Route::get('/ormawa/documents/stats', [DocumentController::class, 'getStats']);
    Route::get('/ormawa/documents', [DocumentController::class, 'getAllDocuments']);
    Route::post('/ormawa/documents/submit', [DocumentController::class, 'submit']);
    Route::get('/ormawa/tujuan-pengajuan', [DocumentController::class, 'getTujuanPengajuan']);
    Route::get('/ormawa/documents/{id}', [DocumentController::class, 'getDocumentDetail']);
    Route::get('/ormawa/documents/{id}/file', [DocumentController::class, 'getDocumentFile']);
    Route::post('/ormawa/documents/{id}/revisi', [DocumentController::class, 'uploadRevisi']);
    Route::put('/ormawa/profile', [OrmawaAuthController::class, 'updateProfile']);
    Route::put('/ormawa/profile/password', [OrmawaAuthController::class, 'updatePassword']);
    
    // Dosen routes
    Route::get('/dosen/document-stats', [DocumentController::class, 'getDosenDocumentStats']);
});