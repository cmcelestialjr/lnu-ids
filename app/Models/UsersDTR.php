<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDTR extends Model
{
    protected $table = 'users_dtr';
    public function user()
    {
        return $this->belongsTo(Users::class, 'id_no', 'id_no')->withDefault();
    }
    public function time_type_()
    {
        return $this->belongsTo(DTRtimeType::class, 'time_type', 'id')->withDefault();
    }
}
