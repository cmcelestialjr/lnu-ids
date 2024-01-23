<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsCoursesPreenroll extends Model
{
    protected $table = 'students_courses_pre_enroll';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
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
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function course()
    {
        return $this->belongsTo(EducCourses::class, 'courses_id', 'id')->withDefault();
    }
    public function year_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'year_level_id', 'id')->withDefault();
    }
    public function preenroll_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
