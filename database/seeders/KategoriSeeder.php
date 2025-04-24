<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Makanan'],
            ['nama_kategori' => 'Minuman'],
            ['nama_kategori' => 'Alat Tulis'],
            ['nama_kategori' => 'Elektronik'],
            ['nama_kategori' => 'Pakaian'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
