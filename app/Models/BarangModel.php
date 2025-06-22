<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriModel;

class BarangModel extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'm_barang';


    protected $primaryKey = 'id';


    public $timestamps = true;

    
    protected $fillable = [
        'nama_barang',
        'kategori_id',
        'supplier_id',
        'stok',
        'harga',
        'barang_kode',
        'harga_beli',
        'harga_jual',
    ];

  
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'id');
    }
}
