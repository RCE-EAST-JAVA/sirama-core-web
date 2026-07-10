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

        Schema::create('form3_in1s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained();
            $table->string('nama_lengkap_pemohon');
            $table->string('desa');
            $table->text('alamat_lengkap');
            $table->string('nama_anak');
            $table->date('tanggal_lahir_anak');
            $table->string('file_sk_lahir');
            $table->string('file_kk');
            $table->string('file_ktp_ortu');
            $table->string('file_surat_nikah');
            $table->string('file_foto_anak');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form3_in1s');
    }
};
