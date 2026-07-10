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
        Schema::disableForeignKeyConstraints();

        Schema::create('form_kk_pengurangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained();
            $table->text('alasan_pengurangan');
            $table->string('nama_lengkap_anggota');
            $table->text('alamat_lengkap_anggota');
            $table->string('nik_anggota');
            $table->string('file_kk_asli');
            $table->string('file_ktp_asli');
            $table->string('file_sk_pindah_mati');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_kk_pengurangans');
    }
};
