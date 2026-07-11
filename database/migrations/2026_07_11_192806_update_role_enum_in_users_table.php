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
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('warga','admin_desa','admin_kecamatan','admin_aplikasi') NOT NULL DEFAULT 'warga'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('warga','admin_desa','admin_kecamatan') NOT NULL DEFAULT 'warga'");
    }
};
