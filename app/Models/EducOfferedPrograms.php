<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedPrograms extends Model
{
    protected $table = 'educ__offered_programs';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function departments()
    {
        return $this->belongsTo(EducDepartments::class, 'department_id', 'id')->withDefault();
    }
    public function program()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
    public function curriculums()
    {
        return $this->hasMany(EducOfferedCurriculum::class, 'offered_program_id', 'id');
    }
}
