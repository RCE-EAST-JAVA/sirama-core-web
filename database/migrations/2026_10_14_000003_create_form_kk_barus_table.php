<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('form_kk_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained();
            $table->string('nama_kepala_keluarga');
            $table->string('nomor_kk_lama');
            $table->string('nama_ketua_rt');
            $table->string('nama_ketua_rw');
            $table->string('nama_lengkap_suami');
            $table->string('tempat_lahir_suami');
            $table->date('tanggal_lahir_suami');
            $table->string('agama_suami');
            $table->string('pekerjaan_suami');
            $table->string('nama_lengkap_istri');
            $table->string('tempat_lahir_istri');
            $table->date('tanggal_lahir_istri');
            $table->string('agama_istri');
            $table->string('pekerjaan_istri');
            $table->string('alamat_baru');
            $table->string('rt');
            $table->string('rw');
            $table->string('desa');
            $table->string('alasan_pembuatan');
            $table->string('file_kk_lama');
            $table->string('file_ktp_suami');
            $table->string('file_ktp_istri');
            $table->string('file_surat_domisili');
            $table->string('file_surat_nikah');
            $table->string('file_ijazah_suami');
            $table->string('file_ijazah_istri');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('form_kk_barus');
    }
};
