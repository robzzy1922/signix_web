<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;

//login
Route::middleware('api')->post('/login', [LoginAuthController::class, 'login']);