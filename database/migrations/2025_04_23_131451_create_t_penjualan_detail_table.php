<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('t_penjualan_detail')) {
            Schema::create('t_penjualan_detail', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('penjualan_id');
                $table->unsignedBigInteger('barang_id');
                $table->integer('jumlah');
                $table->decimal('harga_satuan', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->timestamps();

               
                $table->foreign('penjualan_id')->references('id')->on('t_penjualan')->onDelete('cascade');
                $table->foreign('barang_id')->references('id')->on('m_barang')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_detail');
    }
};
