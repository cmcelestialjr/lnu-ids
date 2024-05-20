<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedCourses extends Model
{
    protected $table = 'educ__offered_courses';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function curriculum()
    {
        return $this->belongsTo(EducOfferedCurriculum::class, 'offered_curriculum_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'course_id', 'id')->withDefault();
    }
    public function nstp()
    {
        return $this->belongsTo(EducCoursesNstp::class, 'nstp_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'status_id', 'id')->withDefault();
    }
    public function instructor()
    {
        return $this->belongsTo(Users::class, 'instructor_id', 'id')->withDefault();
    }
    public function load_type()
    {
        return $this->belongsTo(EducLoadType::class, 'load_type', 'id')->withDefault();
    }
    public function students()
    {
        return $this->hasMany(StudentsCourses::class, 'offered_course_id', 'id');
    }
    public function w_grade()
    {
        return $this->hasMany(StudentsCourses::class, 'offered_course_id', 'id')->where('student_course_status_id','!=',NULL);
    }
    public function wo_grade()
    {
        return $this->hasMany(StudentsCourses::class, 'offered_course_id', 'id')->where('student_course_status_id',NULL);
    }
    public function schedule()
    {
        return $this->hasMany(EducOfferedSchedule::class, 'offered_course_id', 'id')->orderBy('time_from','ASC');
    }
}
