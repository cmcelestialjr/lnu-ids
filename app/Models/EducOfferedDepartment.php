<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedDepartment extends Model
{
    protected $table = 'educ__offered_department';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function programs()
    {
        return $this->hasMany(EducOfferedPrograms::class, ['school_year_id', 'department_id'], ['school_year_id', 'department_id']);
    }
}
