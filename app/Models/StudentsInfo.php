<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsInfo extends Model
{
    protected $table = 'students_info';
    public function program()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function grade_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'grade_level_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
