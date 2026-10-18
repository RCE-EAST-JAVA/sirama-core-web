<?php

use App\Models\MasterJenisPerbaikanKk;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        MasterJenisPerbaikanKk::firstOrCreate(
            ['nama_perbaikan' => 'Perbaikan Pendidikan'],
            ['deskripsi'      => 'Koreksi data pendidikan terakhir anggota keluarga']
        );
    }

    public function down(): void
    {
        MasterJenisPerbaikanKk::where('nama_perbaikan', 'Perbaikan Pendidikan')->delete();
    }
};
