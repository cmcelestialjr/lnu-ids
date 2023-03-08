<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemsNav extends Model
{
    protected $table = 'systems_nav';
    public function system()
    {
        return $this->belongsTo(Systems::class, 'system_id', 'id')->withDefault();
    }
    public function navSubs()
    {
        return $this->hasMany(SystemsNavSub::class, 'system_nav_id', 'id')->orderBy('order');
    }
    public function user_nav()
    {
        return $this->belongsTo(UsersSystemsNav::class, 'id', 'system_nav_id')->withDefault();
    }
}
