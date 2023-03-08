<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersRoleList extends Model
{
    protected $table = 'users_role_list';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function role()
    {
        return $this->belongsTo(UsersRole::class, 'role_id', 'id')->withDefault();
    }
}
