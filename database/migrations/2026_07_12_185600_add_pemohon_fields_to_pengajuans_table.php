<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuans', 'nama_lengkap')) {
                $table->string('nama_lengkap')->nullable()->after('no_whatsapp');
            }
            if (!Schema::hasColumn('pengajuans', 'nik')) {
                $table->string('nik')->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('pengajuans', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('pengajuans', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('pengajuans', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('pengajuans', 'alamat')) {
                $table->text('alamat')->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('pengajuans', 'desa')) {
                $table->string('desa')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pengajuans', 'rt')) {
                $table->string('rt')->nullable()->after('desa');
            }
            if (!Schema::hasColumn('pengajuans', 'rw')) {
                $table->string('rw')->nullable()->after('rt');
            }
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
