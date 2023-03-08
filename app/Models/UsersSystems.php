<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSystems extends Model
{
    protected $table = 'users_systems';
    public function system()
    {
        return $this->belongsTo(Systems::class, 'system_id', 'id')->withDefault();
    }
}
