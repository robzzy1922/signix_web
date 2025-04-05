<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrmawaAuthController;
use App\Http\Controllers\Api\DocumentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/ormawa/login', [OrmawaAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ormawa/logout', [OrmawaAuthController::class, 'logout']);
    Route::get('/ormawa/documents/stats', [DocumentController::class, 'getStats']);
    Route::post('/ormawa/documents/submit', [DocumentController::class, 'submit']);
    Route::get('/ormawa/tujuan-pengajuan', [DocumentController::class, 'getTujuanPengajuan']);
});
