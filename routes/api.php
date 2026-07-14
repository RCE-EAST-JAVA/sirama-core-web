<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Pengajuan\BasePengajuanController;
use App\Http\Controllers\Api\Pengajuan\KiaPengajuanController;
use App\Http\Controllers\Api\Pengajuan\Tiga1PengajuanController;
use App\Http\Controllers\Api\Pengajuan\KkPenambahanPengajuanController;
use App\Http\Controllers\Api\Pengajuan\KkPenguranganPengajuanController;
use App\Http\Controllers\Api\Pengajuan\KkPerbaikanPengajuanController;
use App\Http\Controllers\Api\Pengajuan\AktaLahirPengajuanController;
use App\Http\Controllers\Api\Pengajuan\AktaKematianPengajuanController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DesaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis mendapat prefix /api
| Auth menggunakan Laravel Sanctum (token-based)
*/

// Public routes - tidak perlu token
Route::get('/desas', [DesaController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected routes - perlu token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Pengajuan - shared endpoints (index, show, status)
    // Gunakan KiaPengajuanController sebagai concrete class untuk shared methods
    Route::get('/pengajuan/stats',               [KiaPengajuanController::class, 'stats']);
    Route::get('/pengajuan',                    [KiaPengajuanController::class, 'index']);
    Route::get('/pengajuan/{pengajuan}',         [KiaPengajuanController::class, 'show']);
    Route::get('/pengajuan/{pengajuan}/status',  [KiaPengajuanController::class, 'status']);

    // KIA - Kartu Identitas Anak
    Route::post('/pengajuan/kia',               [KiaPengajuanController::class, 'store']);
    Route::post('/pengajuan/kia/{pengajuan}',    [KiaPengajuanController::class, 'update']);

    // 3 in 1 - Akta Kelahiran + KK + KIA
    Route::post('/pengajuan/3-in-1',            [Tiga1PengajuanController::class, 'store']);
    Route::post('/pengajuan/3-in-1/{pengajuan}', [Tiga1PengajuanController::class, 'update']);

    // KK Penambahan
    Route::post('/pengajuan/kk-penambahan',               [KkPenambahanPengajuanController::class, 'store']);
    Route::post('/pengajuan/kk-penambahan/{pengajuan}',    [KkPenambahanPengajuanController::class, 'update']);

    // KK Pengurangan
    Route::post('/pengajuan/kk-pengurangan',              [KkPenguranganPengajuanController::class, 'store']);
    Route::post('/pengajuan/kk-pengurangan/{pengajuan}',   [KkPenguranganPengajuanController::class, 'update']);

    // KK Perbaikan
    Route::post('/pengajuan/kk-perbaikan',                [KkPerbaikanPengajuanController::class, 'store']);
    Route::post('/pengajuan/kk-perbaikan/{pengajuan}',     [KkPerbaikanPengajuanController::class, 'update']);

    // Akta Lahir
    Route::post('/pengajuan/akta-lahir',               [AktaLahirPengajuanController::class, 'store']);
    Route::post('/pengajuan/akta-lahir/{pengajuan}',    [AktaLahirPengajuanController::class, 'update']);

    // Akta Kematian
    Route::post('/pengajuan/akta-kematian',              [AktaKematianPengajuanController::class, 'store']);
    Route::post('/pengajuan/akta-kematian/{pengajuan}',   [AktaKematianPengajuanController::class, 'update']);

    // Profile
    Route::get('/profile',      [ProfileController::class, 'show']);
    Route::post('/profile',     [ProfileController::class, 'update']);
    Route::get('/profile/foto', [ProfileController::class, 'foto'])->name('api.profile.foto');
});
