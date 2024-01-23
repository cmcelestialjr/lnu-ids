<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDepartmentUnit extends Model
{
    protected $table = 'educ_department_unit';
    public function department()
    {
        return $this->belongsTo(EducDepartments::class, 'department_id', 'id')->withDefault();
    }
    public function programs()
    {
        return $this->hasMany(EducPrograms::class, 'department_unit_id', 'id');
    }
}
