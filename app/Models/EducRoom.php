<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducRoom extends Model
{
    protected $table = 'educ_rooms';
    public function rooms()
    {
        return $this->hasMany(EducOfferedSchedule::class, 'room_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}