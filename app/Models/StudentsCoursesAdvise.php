<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCoursesAdvise extends Model
{
    protected $table = 'students_courses_advise';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducOfferedCourses::class, 'offered_course_id', 'id')->withDefault();
    }
    public function curriculum()
    {
        return $this->belongsTo(EducOfferedCurriculum::class, 'offered_curriculum', 'id')->withDefault();
    }
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function advised_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
