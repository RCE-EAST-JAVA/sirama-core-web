<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // nama_lengkap dan nik sudah ada — hanya tambah kolom baru
            $table->date('tanggal_lahir')->nullable()->after('nik');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->string('pekerjaan')->nullable()->after('jenis_kelamin');
            $table->text('alamat')->nullable()->after('pekerjaan');
            $table->string('desa')->nullable()->after('alamat');
            $table->string('rt')->nullable()->after('desa');
            $table->string('rw')->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap', 'nik', 'tanggal_lahir', 'jenis_kelamin',
                'pekerjaan', 'alamat', 'desa', 'rt', 'rw',
            ]);
        });
    }
};
