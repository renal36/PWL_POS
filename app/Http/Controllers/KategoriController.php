<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        
        $data = [
            'nama_kategori' => 'Snack/Makanan Ringan',
            'deskripsi' => 'Kategori untuk makanan ringan',
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('m_kategori')->insert($data);
        echo 'Insert data baru berhasil<br>';

        $data = DB ::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);
    }
}
