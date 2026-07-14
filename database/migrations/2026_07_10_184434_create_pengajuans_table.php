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

        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('jenis_layanan', ["kia","3_in_1","kk_penambahan","kk_pengurangan","kk_perbaikan","akta_kelahiran","akta_kematian"]);
            $table->enum('status', ["berkas_diterima","ditolak_desa","diverifikasi_desa","ditolak_kecamatan","diverifikasi_kecamatan","selesai"])->default('berkas_diterima');
            $table->string('lokasi_dokumen')->nullable();
            $table->string('no_whatsapp');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
