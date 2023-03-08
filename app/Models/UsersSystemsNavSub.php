<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSystemsNavSub extends Model
{
    protected $table = 'users_systems_nav_sub';
    public function systems_nav_sub()
    {
        return $this->belongsTo(SystemsNavSub::class, 'system_nav_sub_id', 'id')->withDefault();
    }
}
