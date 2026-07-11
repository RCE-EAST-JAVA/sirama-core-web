<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Desa\DashboardController as DesaDashboardController;
use App\Http\Controllers\Desa\PengajuanController as DesaPengajuanController;
use App\Http\Controllers\Kecamatan\DashboardController as KecamatanDashboardController;
use App\Http\Controllers\Kecamatan\PengajuanController as KecamatanPengajuanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard warga (Breeze default, akan digunakan sementara)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Aplikasi Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin_aplikasi'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen User (admin desa & kecamatan)
    Route::resource('users', AdminUserController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Admin Desa Routes
|--------------------------------------------------------------------------
*/
Route::prefix('desa')->name('desa.')->middleware(['auth', 'role:admin_desa'])->group(function () {
    Route::get('/dashboard', [DesaDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/',                          [DesaPengajuanController::class, 'index'])->name('index');
        Route::get('/{pengajuan}',               [DesaPengajuanController::class, 'show'])->name('show');
        Route::post('/{pengajuan}/verifikasi',   [DesaPengajuanController::class, 'verifikasi'])->name('verifikasi');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Kecamatan Routes
|--------------------------------------------------------------------------
*/
Route::prefix('kecamatan')->name('kecamatan.')->middleware(['auth', 'role:admin_kecamatan'])->group(function () {
    Route::get('/dashboard', [KecamatanDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/',                        [KecamatanPengajuanController::class, 'index'])->name('index');
        Route::get('/{pengajuan}',             [KecamatanPengajuanController::class, 'show'])->name('show');
        Route::post('/{pengajuan}/proses',     [KecamatanPengajuanController::class, 'proses'])->name('proses');
    });
});

require __DIR__.'/auth.php';
