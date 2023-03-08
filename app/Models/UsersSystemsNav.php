<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSystemsNav extends Model
{
    protected $table = 'users_systems_nav';
    public function systems_nav()
    {
        return $this->belongsTo(SystemsNav::class, 'system_nav_id', 'id')->withDefault();
    }
}
