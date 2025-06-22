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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' setelah 'username' (atau setelah email jika username belum ada di migrasi Anda)
            // Beri nilai default jika user lama tidak memiliki role
            $table->string('role')->default('user')->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ketika migrasi di-rollback, hapus kolom 'role'
            $table->dropColumn('role');
        });
    }
};