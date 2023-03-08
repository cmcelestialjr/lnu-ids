<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemsNavSub extends Model
{
    protected $table = 'systems_nav_sub';
    
    public function system_nav()
    {
        return $this->belongsTo(SystemsNav::class, 'system_nav_id', 'id')->withDefault();
    }
    public function user_nav_sub()
    {
        return $this->belongsTo(UsersSystemsNavSub::class, 'id', 'system_nav_sub_id')->withDefault();
    }
}
