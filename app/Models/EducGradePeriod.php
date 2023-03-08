<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducGradePeriod extends Model
{
    protected $table = 'educ_grade_period';
    public function courses()
    {
        return $this->hasMany(EducCourses::class, 'grade_period_id', 'id')
                ->orderBy('grade_level_id','ASC')
                ->orderBy('grade_period_id','ASC')
                ->orderBy('id','ASC');
    }
}
