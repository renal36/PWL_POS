<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    protected $table      = 'm_kategori';   
    protected $primaryKey = 'id';           
    public    $timestamps = true;           

    protected $fillable = ['nama_kategori', 'deskripsi'];


    public function getKategoriIdAttribute(): int
    {
        return $this->attributes['id'];
    }

    public function getKategoriNamaAttribute(): string
    {
        return $this->attributes['nama_kategori'];
    }
}
