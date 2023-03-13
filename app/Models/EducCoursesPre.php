<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCoursesPre extends Model
{
    protected $table = 'educ_courses_pre';
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'course_id', 'id')->withDefault();
    }
    public function pre()
    {
        return $this->belongsTo(EducCourses::class, 'pre_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'update_by', 'id')->withDefault();
    }
}
