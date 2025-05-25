<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user';  
    protected $primaryKey = 'user_id'; 
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;  

    protected $fillable = [
        'username',
        'level_id',
        'nama',
        'password'
    ];

    /**
     * Relasi ke tabel level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
        // 'level_id' pertama adalah foreign key di m_user,
        // 'level_id' kedua adalah primary key di m_level (LevelModel)
    }

    /**
     * Hidden fields supaya password tidak tampil di array/json secara default
     */
    protected $hidden = [
        'password',
    ];
}
