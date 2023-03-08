<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducPrograms extends Model
{
    protected $table = 'educ_programs';
    public function departments()
    {
        return $this->belongsTo(EducDepartments::class, 'department_id', 'id')->withDefault();
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'status_id', 'id')->withDefault();
    }
    public function codes()
    {
        return $this->hasMany(EducProgramsCode::class, 'program_id', 'id');
    }
    public function curriculum()
    {
        return $this->hasMany(EducCurriculum::class, 'program_id', 'id')->orderBy('year_from','DESC');
    }
}
