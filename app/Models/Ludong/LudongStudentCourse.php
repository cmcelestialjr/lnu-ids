<?php

namespace App\Models\Ludong;

use App\Models\EducPrograms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongStudentCourse extends Model
{
    protected $connection = 'student';
    protected $table = 'course';
    public function course_info()
    {
        return $this->belongsTo(LudongCourses::class, 'course', 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(LudongStudents::class, 'stud_id', 'stud_id');
    }
}
