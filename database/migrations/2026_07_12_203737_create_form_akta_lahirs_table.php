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
        Schema::create('form_akta_lahirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();
            $table->string('nama_anak');
            $table->date('tanggal_lahir_anak');
            $table->string('file_sk_lahir');
            $table->string('file_kk');
            $table->string('file_ktp_ayah');
            $table->string('file_ktp_ibu');
            $table->string('file_surat_nikah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_akta_lahirs');
    }
};
