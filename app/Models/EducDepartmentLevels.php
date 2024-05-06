<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDepartmentLevels extends Model
{
    protected $table = 'educ_department_levels';
    public function department()
    {
        return $this->belongsTo(EducDepartments::class, 'department_id', 'id');
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id');
    }

}
