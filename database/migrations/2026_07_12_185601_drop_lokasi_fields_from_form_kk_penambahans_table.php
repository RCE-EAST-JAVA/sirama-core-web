<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_kk_penambahans', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'nama_dusun', 'rt', 'rw']);
        });
    }

    public function down(): void
    {
        Schema::table('form_kk_penambahans', function (Blueprint $table) {
            $table->text('alamat')->after('nomor_kk');
            $table->string('nama_dusun')->after('alamat');
            $table->string('rt')->after('nama_dusun');
            $table->string('rw')->after('rt');
        });
    }
};
