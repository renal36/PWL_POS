<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'barang_id' => 1,
                'jumlah' => 2,
                'harga_satuan' => 15000.00,
                'subtotal' => 2 * 15000.00,
            ],
            [
                'penjualan_id' => 2,
                'barang_id' => 2,
                'jumlah' => 5,
                'harga_satuan' => 5000.00,
                'subtotal' => 5 * 5000.00,
            ],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
