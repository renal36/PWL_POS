<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'jenis' => 'masuk',
                'jumlah' => 100,
                'tanggal' => Carbon::now(),
            ],
            [
                'barang_id' => 2,
                'jenis' => 'masuk',
                'jumlah' => 200,
                'tanggal' => Carbon::now(),
            ],
            [
                'barang_id' => 1,
                'jenis' => 'keluar',
                'jumlah' => 10,
                'tanggal' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('t_stok')->insert($data);
    }
}
