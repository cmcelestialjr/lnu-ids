<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCourseStatus extends Model
{
    protected $table = 'students_course_status';
    public function students()
    {
        return $this->hasMany(StudentsCourses::class, 'student_course_status_id', 'id');
    }
}