<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'username' setelah 'email' atau 'name'
            $table->string('username')->unique()->after('email'); // Sesuaikan posisi jika perlu
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom 'username' jika migrasi di-rollback
            $table->dropColumn('username');
        });
    }
};