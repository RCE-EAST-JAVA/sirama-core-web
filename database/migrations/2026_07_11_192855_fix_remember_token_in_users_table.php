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
        if (Schema::hasColumn('users', 'rememberToken') && !Schema::hasColumn('users', 'remember_token')) {
            \DB::statement("ALTER TABLE users CHANGE rememberToken remember_token VARCHAR(100) NULL DEFAULT NULL");
        }
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE users CHANGE remember_token rememberToken VARCHAR(255) NOT NULL");
    }
};
