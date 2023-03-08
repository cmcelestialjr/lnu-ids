<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedCurriculum extends Model
{
    protected $table = 'educ__offered_curriculum';
    public function offered_program()
    {
        return $this->belongsTo(EducOfferedPrograms::class, 'offered_program_id', 'id')->withDefault();
    }
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function offered_courses()
    {
        return $this->hasMany(EducOfferedCourses::class, 'offered_curriculum_id', 'id');
    }
    public function courses()
    {
        return $this->hasMany(EducCourses::class, 'curriculum_id', 'curriculum_id');
    }
}
