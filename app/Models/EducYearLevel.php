<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducYearLevel extends Model
{
    protected $table = 'educ_year_level';
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function courses()
    {
        return $this->hasMany(EducCourses::class, 'grade_level_id', 'id')
                ->orderBy('grade_level_id','ASC')
                ->orderBy('grade_period_id','ASC')
                ->orderBy('id','ASC');
    }
}
