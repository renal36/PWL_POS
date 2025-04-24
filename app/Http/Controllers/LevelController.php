<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel m_level
        $levels = DB::table('m_level')->get();
        return view('level.index', compact('levels'));
    }
}
