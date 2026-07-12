<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form3_in1s', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap_pemohon', 'desa', 'alamat_lengkap']);
        });
    }

    public function down(): void
    {
        Schema::table('form3_in1s', function (Blueprint $table) {
            $table->string('nama_lengkap_pemohon')->after('pengajuan_id');
            $table->string('desa')->after('nama_lengkap_pemohon');
            $table->text('alamat_lengkap')->after('desa');
        });
    }
};
