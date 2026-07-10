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

        Schema::create('form_kk_perbaikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained();
            $table->foreignId('jenis_perbaikan_id')->constrained('master_jenis_perbaikan_kks');
            $table->string('nama_kepala_keluarga');
            $table->string('nomor_kk');
            $table->string('nama_anggota_yang_diperbaiki');
            $table->json('data_perbaikan')->nullable();
            $table->json('file_pendukung')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_kk_perbaikans');
    }
};
