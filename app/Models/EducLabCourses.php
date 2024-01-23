<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducLabCourses extends Model
{
    protected $table = 'educ_lab_courses';
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'course_code', 'code');
    }
    public function group()
    {
        return $this->belongsTo(EducLabGroup::class, 'lab_group_id', 'id');
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
