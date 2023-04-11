<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedScheduleDay extends Model
{
    protected $table = 'educ__offered_schedule_day';
    public function schedule()
    {
        return $this->belongsTo(EducOfferedSchedule::class, 'offered_schedule_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}