<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedCourses extends Model
{
    protected $table = 'educ__offered_courses';
    public function curriculum()
    {
        return $this->belongsTo(EducOfferedCurriculum::class, 'offered_curriculum_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'course_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'course_status_id', 'id')->withDefault();
    }
}
