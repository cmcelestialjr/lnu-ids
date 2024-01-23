<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCourses extends Model
{
    protected $table = 'students_courses';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducOfferedCourses::class, 'offered_course_id', 'id')->withDefault();
    }
    public function course_info()
    {
        return $this->belongsTo(EducCourses::class, 'course_id', 'id')->withDefault();
    }
    public function course_credit()
    {
        return $this->belongsTo(EducCourses::class, 'credit_course_id', 'id')->withDefault();
    }
    public function program()
    {
        return $this->belongsTo(StudentsProgram::class, 'student_program_id', 'id')->withDefault();
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function grade_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'grade_level_id', 'id')->withDefault();
    }
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(StudentsCourseStatus::class, 'student_course_status_id', 'id')->withDefault();
    }
    public function student_info()
    {
        return $this->belongsTo(StudentsInfo::class, 'user_id', 'user_id')->withDefault();
    }
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function graded_by()
    {
        return $this->belongsTo(Users::class, 'graded_by', 'id')->withDefault();
    }
    public function credited_by()
    {
        return $this->belongsTo(Users::class, 'credited_by_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
