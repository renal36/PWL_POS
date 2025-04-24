<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'jumlah' => 2,
                'total_harga' => 30000.00, // contoh: 2 x 15000
                'tanggal_penjualan' => Carbon::now(),
            ],
            [
                'barang_id' => 2,
                'jumlah' => 5,
                'total_harga' => 25000.00, // contoh: 5 x 5000
                'tanggal_penjualan' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}

