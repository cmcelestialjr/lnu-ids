<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongCourses extends Model
{
    protected $connection = 'courses';
    protected $table = 'info';
    public function student_course()
    {
        return $this->hasMany(LudongStudentCourse::class, 'course_id', 'course');
    }
}
