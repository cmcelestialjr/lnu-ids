<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCurriculum extends Model
{
    protected $table = 'educ_curriculum';
    public function programs()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'status_id', 'id')->withDefault();
    }
    public function courses()
    {
        return $this->hasMany(EducCourses::class, 'curriculum_id', 'id')
                ->orderBy('grade_level_id','ASC')
                ->orderBy('grade_period_id','ASC')
                ->orderBy('id','ASC');
    }
}
