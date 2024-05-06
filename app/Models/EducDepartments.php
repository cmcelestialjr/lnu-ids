<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDepartments extends Model
{
    protected $table = 'educ_department';
    public function programs()
    {
        return $this->hasMany(EducPrograms::class, 'department_id', 'id');
    }
    public function levels()
    {
        return $this->hasMany(EducDepartmentLevels::class, 'department_id', 'id');
    }
    public function offered_department()
    {
        return $this->hasMany(EducOfferedDepartment::class, 'department_id', 'id');
    }
}
