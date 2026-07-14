<?php

namespace App\Providers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Explicit route model binding untuk Pengajuan
        Route::bind('pengajuan', function (string $value) {
            return Pengajuan::findOrFail($value);
        });
    }
}
