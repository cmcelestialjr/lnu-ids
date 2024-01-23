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
    public function department()
    {
        return $this->belongsTo(EducDepartments::class, 'department_id', 'id')->withDefault();
    }
    public function program()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
    public function program_code()
    {
        return $this->belongsTo(EducProgramsCode::class, 'program_code_id', 'id')->withDefault();
    }
    public function branch()
    {
        return $this->belongsTo(EducBranch::class, 'branch_id', 'id')->withDefault();
    }
    public function curriculums()
    {
        return $this->hasMany(EducOfferedCurriculum::class, 'offered_program_id', 'id');
    }
    public function offered_program()
    {
        return $this->hasMany(EducOfferedPrograms::class, 'program_id', 'id');
    }
}
