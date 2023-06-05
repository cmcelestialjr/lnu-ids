<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducLoadType extends Model
{
    protected $table = 'educ_load_type';
    public function courses()
    {
        return $this->hasMany(EducOfferedCourses::class, 'load_type', 'id')
                ->orderBy('grade_level_id','ASC')
                ->orderBy('grade_period_id','ASC')
                ->orderBy('id','ASC');
    }
}
