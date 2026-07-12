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
        Schema::create('form_akta_kematians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();
            $table->string('nama_lengkap_anggota');
            $table->text('alamat_lengkap_anggota');
            $table->string('nik_anggota', 16);
            $table->string('file_kk_asli');
            $table->string('file_ktp_asli');
            $table->string('file_sk_kematian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_akta_kematians');
    }
};
