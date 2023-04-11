<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedSchedule extends Model
{
    protected $table = 'educ__offered_schedule';
    public function course()
    {
        return $this->belongsTo(EducOfferedCourses::class, 'offered_course_id', 'id')->withDefault();
    }
    public function room()
    {
        return $this->belongsTo(EducRoom::class, 'room_id', 'id')->withDefault();
    }
    public function days()
    {
        return $this->hasMany(EducOfferedScheduleDay::class, 'offered_schedule_id', 'id')->orderBy('no','ASC');
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}