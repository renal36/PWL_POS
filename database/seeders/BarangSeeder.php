<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_barang' => 'Nasi Goreng',
                'kategori_id' => 1,
                'supplier_id' => 1,
                'stok' => 100,
                'harga' => 15000.00,
            ],
            [
                'nama_barang' => 'Teh Botol',
                'kategori_id' => 2,
                'supplier_id' => 2,
                'stok' => 200,
                'harga' => 5000.00,
            ],
            [
                'nama_barang' => 'Pulpen Biru',
                'kategori_id' => 3,
                'supplier_id' => 3,
                'stok' => 300,
                'harga' => 2500.00,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}


