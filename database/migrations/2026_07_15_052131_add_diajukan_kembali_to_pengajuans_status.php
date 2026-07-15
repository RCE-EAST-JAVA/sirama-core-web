<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->enum('status', [
                'berkas_diterima',
                'diajukan_kembali',
                'ditolak_desa',
                'diverifikasi_desa',
                'ditolak_kecamatan',
                'diverifikasi_kecamatan',
                'selesai',
            ])->default('berkas_diterima')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->enum('status', [
                'berkas_diterima',
                'ditolak_desa',
                'diverifikasi_desa',
                'ditolak_kecamatan',
                'diverifikasi_kecamatan',
                'selesai',
            ])->default('berkas_diterima')->change();
        });
    }
};
