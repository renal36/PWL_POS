<?php

namespace App\Imports;

use App\Models\BarangModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BarangModel([
            'kategori_id'  => $row['kategori_id'],
            'barang_kode'  => $row['barang_kode'],
            'nama_barang'  => $row['barang_nama'],
            'harga_beli'   => $row['harga_beli'],
            'harga_jual'   => $row['harga_jual'],
            'harga'        => $row['harga_jual'], // gunakan harga jual
            'stok'         => 0,                  // default stok = 0
            'supplier_id'  => 1,                  // ⚠️ supplier_id default
        ]);
    }
}
