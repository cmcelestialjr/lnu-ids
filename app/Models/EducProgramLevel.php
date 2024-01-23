<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducProgramLevel extends Model
{
    protected $table = 'educ_programs_level';
    public function year_level()
    {
        return $this->hasMany(EducYearLevel::class, 'program_level_id', 'id');
    }
    public function students_courses()
    {
        return $this->hasMany(StudentsCourses::class, 'program_level_id', 'id');
    }
    public function student_programs()
    {
        return $this->hasMany(StudentsProgram::class, 'program_level_id', 'id');
    }
    public function education_bg()
    {
        return $this->hasMany(_EducationBg::class, 'level_id', 'id')->orderBy('period_from','DESC');
    }
}
