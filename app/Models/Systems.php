<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systems extends Model
{
    protected $table = 'systems';
    public function navs()
    {
        return $this->hasMany(SystemsNav::class, 'system_id', 'id')->orderBy('order');
    }
    public function user_system()
    {
        return $this->belongsTo(UsersSystems::class, 'id', 'system_id')->withDefault();
    }
}
