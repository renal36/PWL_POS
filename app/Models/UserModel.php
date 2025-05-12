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

    public $timestamps = true;  

    protected $fillable = [
        'username',
        'level_id',
        'nama',
        'password'
    ];
    
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'id');
    }
}

