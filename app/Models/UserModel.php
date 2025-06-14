<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LevelModel;


class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user';  // tabel user

    protected $primaryKey = 'user_id';  // primary key user

    protected $fillable = [
        'username', 'nama', 'password', 'level_id'
    ];

    
   public function level()
{
    return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
}

}
