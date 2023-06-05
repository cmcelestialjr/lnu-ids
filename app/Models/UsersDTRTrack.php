<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDTRTrack extends Model
{
    protected $table = 'users_dtr_track';
    public function user()
    {
        return $this->belongsTo(Users::class, 'id_no', 'id_no')->withDefault();
    }
}
