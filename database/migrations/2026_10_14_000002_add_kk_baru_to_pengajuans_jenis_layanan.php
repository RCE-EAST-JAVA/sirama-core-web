<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN jenis_layanan ENUM(
            'kia',
            '3_in_1',
            'kk_penambahan',
            'kk_pengurangan',
            'kk_perbaikan',
            'akta_kelahiran',
            'akta_kematian',
            'kk_baru'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN jenis_layanan ENUM(
            'kia',
            '3_in_1',
            'kk_penambahan',
            'kk_pengurangan',
            'kk_perbaikan',
            'akta_kelahiran',
            'akta_kematian'
        ) NOT NULL");
    }
};
