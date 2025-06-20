<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable; // Tambahkan ini
use App\Models\LevelModel;


class UserModel extends Authenticatable // Ubah dari Model menjadi Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';    // tabel user

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username', 'nama', 'password', 'level_id'
    ];

    protected $hidden = ['password']; // Tambahkan ini untuk menyembunyikan password

    protected $casts = ['password' => 'hashed'];

    /**
     * Relasi ke tabel level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole(): string
    {
        return $this->level->level_kode;
    }
}