<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrasi data lama: string tunggal → JSON array
        DB::table('pengajuans')
            ->whereNotNull('lokasi_dokumen')
            ->whereRaw("lokasi_dokumen NOT LIKE '[%'")
            ->update([
                'lokasi_dokumen' => DB::raw("JSON_ARRAY(lokasi_dokumen)"),
            ]);

        Schema::table('pengajuans', function (Blueprint $table) {
            $table->json('lokasi_dokumen')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('lokasi_dokumen')->nullable()->change();
        });
    }
};
