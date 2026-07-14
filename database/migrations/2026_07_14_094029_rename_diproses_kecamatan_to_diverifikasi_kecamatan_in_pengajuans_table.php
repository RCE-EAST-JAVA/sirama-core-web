<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing rows first, before changing the enum
        DB::table('pengajuans')
            ->where('status', 'diproses_kecamatan')
            ->update(['status' => 'diverifikasi_kecamatan']);

        DB::table('riwayat_statuses')
            ->where('status_riwayat', 'diproses_kecamatan')
            ->update(['status_riwayat' => 'diverifikasi_kecamatan']);

        // Alter the enum column
        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN status ENUM('berkas_diterima','ditolak_desa','diverifikasi_desa','ditolak_kecamatan','diverifikasi_kecamatan','selesai') NOT NULL DEFAULT 'berkas_diterima'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pengajuans')
            ->where('status', 'diverifikasi_kecamatan')
            ->update(['status' => 'diproses_kecamatan']);

        DB::table('riwayat_statuses')
            ->where('status_riwayat', 'diverifikasi_kecamatan')
            ->update(['status_riwayat' => 'diproses_kecamatan']);

        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN status ENUM('berkas_diterima','ditolak_desa','diverifikasi_desa','ditolak_kecamatan','diproses_kecamatan','selesai') NOT NULL DEFAULT 'berkas_diterima'");
    }
};
