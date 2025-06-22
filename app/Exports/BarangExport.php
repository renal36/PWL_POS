<?php

namespace App\Exports;

use App\Models\BarangModel; // Ganti ini
use Maatwebsite\Excel\Concerns\FromCollection;

class BarangExport implements FromCollection
{
    public function collection()
    {
        return BarangModel::all(); // Ganti ini
    }
}
