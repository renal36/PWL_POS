<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('t_stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
    
            $table->foreign('barang_id')->references('id')->on('m_barang')->onDelete('cascade');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('t_stok');
    }
};
