<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level';  // pastikan nama tabel sesuai

    protected $primaryKey = 'level_id';  // pastikan sesuai struktur tabel

    public $timestamps = false;  // biasanya level tidak perlu timestamps

    protected $fillable = [
        'level_nama'
    ];
}
