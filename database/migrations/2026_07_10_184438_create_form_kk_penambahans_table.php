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

        Schema::create('form_kk_penambahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained();
            $table->string('nama_kepala_keluarga');
            $table->string('nomor_kk');
            $table->text('alamat');
            $table->string('nama_dusun');
            $table->string('rt');
            $table->string('rw');
            $table->string('nama_ketua_rt');
            $table->string('nama_ketua_rw');
            $table->string('nama_lengkap_tambahan');
            $table->enum('jenis_kelamin_tambahan', ["L","P"]);
            $table->string('tempat_lahir_tambahan');
            $table->date('tanggal_lahir_tambahan');
            $table->string('status_hubungan');
            $table->string('kelainan_fisik_mental');
            $table->string('penyandang_cacat');
            $table->string('agama');
            $table->string('nama_ibu_kandung');
            $table->string('nik_ibu');
            $table->string('nama_ayah_kandung');
            $table->string('nik_ayah');
            $table->string('file_kk_asli');
            $table->string('file_sk_lahir_akta');
            $table->string('file_ktp_suami_istri');
            $table->string('file_surat_nikah');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_kk_penambahans');
    }
};
