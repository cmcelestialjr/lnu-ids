<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCoursesType extends Model
{
    protected $table = 'educ_course_type';
    public function course_type()
    {
        return $this->hasMany(EducCourses::class, 'course_type_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
