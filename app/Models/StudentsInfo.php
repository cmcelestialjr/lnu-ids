<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsInfo extends Model
{
    protected $table = 'students_info';
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
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
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function grade_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'grade_level_id', 'id')->withDefault();
    }    
    public function status()
    {
        return $this->belongsTo(StudentsStatus::class, 'student_status_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
    public function courses()
    {
        return $this->hasMany(StudentsCourses::class, 'user_id', 'user_id');
    }
    public function student_program()
    {
        return $this->hasMany(StudentsProgram::class, 'user_id', 'user_id');
    }
}
