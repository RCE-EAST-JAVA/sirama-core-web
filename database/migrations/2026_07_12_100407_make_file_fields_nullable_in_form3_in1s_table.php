<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // form3_in1s - 5 file fields
        Schema::table('form3_in1s', function (Blueprint $table) {
            $table->string('file_sk_lahir')->nullable()->change();
            $table->string('file_kk')->nullable()->change();
            $table->string('file_ktp_ortu')->nullable()->change();
            $table->string('file_surat_nikah')->nullable()->change();
            $table->string('file_foto_anak')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('form3_in1s', function (Blueprint $table) {
            $table->string('file_sk_lahir')->nullable(false)->change();
            $table->string('file_kk')->nullable(false)->change();
            $table->string('file_ktp_ortu')->nullable(false)->change();
            $table->string('file_surat_nikah')->nullable(false)->change();
            $table->string('file_foto_anak')->nullable(false)->change();
        });
    }
};
