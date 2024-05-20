<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCoursesNstp extends Model
{
    protected $table = 'educ_courses_nstp';
    public function offered()
    {
        return $this->hasMany(EducOfferedCourses::class, 'nstp_id', 'id');
    }
}
