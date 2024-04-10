<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCoursesCredit extends Model
{
    protected $table = 'students_courses_credit';
    public function student_course()
    {
        return $this->belongsTo(StudentsCourses::class, 'student_course_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'course_id', 'id')->withDefault();
    }
    public function credited_by()
    {
        return $this->belongsTo(Users::class, 'credited_by_id', 'id')->withDefault();
    }
}
