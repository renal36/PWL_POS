// C:\xampp\htdocs\PWL_POS\database\migrations\2025_04_23_131050_create_t_penjualam_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_penjualan', function (Blueprint $table) { // <<< UBAH KE 't_penjualan'
            $table->id('penjualan_id'); // Atau cukup $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key ke tabel users
            $table->string('kode_penjualan')->unique();
            $table->timestamp('tanggal_penjualan')->useCurrent();
            $table->decimal('total_harga', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_penjualan'); // <<< UBAH KE 't_penjualan'
    }
};