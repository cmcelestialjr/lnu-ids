<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedSchoolYear extends Model
{
    protected $table = 'educ__offered_school_year';
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id')->withDefault();
    }
    public function offered_program()
    {
        return $this->hasMany(EducOfferedPrograms::class, 'school_year_id', 'id');
    }
}
