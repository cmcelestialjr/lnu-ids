<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCourses extends Model
{
    protected $table = 'students_courses';
    public function course()
    {
        return $this->belongsTo(EducOfferedCourses::class, 'offered_course_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(StudentsCourseStatus::class, 'student_course_status_id', 'id')->withDefault();
    }
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function graded_by()
    {
        return $this->belongsTo(Users::class, 'graded_by', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
