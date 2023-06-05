<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersAlu extends Model
{
    protected $table = 'users_dtr';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
}
