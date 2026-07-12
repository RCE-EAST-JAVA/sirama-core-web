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
        // form_kias - 4 file fields
        Schema::table('form_kias', function (Blueprint $table) {
            $table->string('file_akta_kelahiran')->nullable()->change();
            $table->string('file_kk')->nullable()->change();
            $table->string('file_surat_nikah')->nullable()->change();
            $table->string('file_foto_anak')->nullable()->change();
        });

        // form_kk_penambahans - 4 file fields
        Schema::table('form_kk_penambahans', function (Blueprint $table) {
            $table->string('file_kk_asli')->nullable()->change();
            $table->string('file_sk_lahir_akta')->nullable()->change();
            $table->string('file_ktp_suami_istri')->nullable()->change();
            $table->string('file_surat_nikah')->nullable()->change();
        });

        // form_kk_pengurangans - 3 file fields
        Schema::table('form_kk_pengurangans', function (Blueprint $table) {
            $table->string('file_kk_asli')->nullable()->change();
            $table->string('file_ktp_asli')->nullable()->change();
            $table->string('file_sk_pindah_mati')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to non-nullable
        Schema::table('form_kias', function (Blueprint $table) {
            $table->string('file_akta_kelahiran')->nullable(false)->change();
            $table->string('file_kk')->nullable(false)->change();
            $table->string('file_surat_nikah')->nullable(false)->change();
            $table->string('file_foto_anak')->nullable(false)->change();
        });

        Schema::table('form_kk_penambahans', function (Blueprint $table) {
            $table->string('file_kk_asli')->nullable(false)->change();
            $table->string('file_sk_lahir_akta')->nullable(false)->change();
            $table->string('file_ktp_suami_istri')->nullable(false)->change();
            $table->string('file_surat_nikah')->nullable(false)->change();
        });

        Schema::table('form_kk_pengurangans', function (Blueprint $table) {
            $table->string('file_kk_asli')->nullable(false)->change();
            $table->string('file_ktp_asli')->nullable(false)->change();
            $table->string('file_sk_pindah_mati')->nullable(false)->change();
        });
    }
};
