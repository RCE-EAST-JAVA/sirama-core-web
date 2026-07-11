<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis mendapat prefix /api
| Auth menggunakan Laravel Sanctum (token-based)
*/

// Public routes - tidak perlu token
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected routes - perlu token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Pengajuan
    Route::get('/pengajuan',                   [PengajuanController::class, 'index']);
    Route::post('/pengajuan',                  [PengajuanController::class, 'store']);
    Route::get('/pengajuan/{pengajuan}',        [PengajuanController::class, 'show']);
    Route::put('/pengajuan/{pengajuan}',        [PengajuanController::class, 'update']);
    Route::post('/pengajuan/{pengajuan}/form',  [PengajuanController::class, 'submitForm']);
    Route::post('/pengajuan/{pengajuan}/dokumen', [PengajuanController::class, 'uploadDokumen']);
    Route::get('/pengajuan/{pengajuan}/status', [PengajuanController::class, 'status']);
});
