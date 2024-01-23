<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsProgram extends Model
{
    protected $table = 'students_program';
    public function info()
    {
        return $this->belongsTo(StudentsInfo::class, 'user_id', 'user_id')->withDefault();
    }
    public function program_info()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function grade_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'grade_level_id', 'id')->withDefault();
    }
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
    public function courses()
    {
        return $this->hasMany(StudentsCourses::class, 'student_program_id', 'id');
    }
}
