<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducProgramLevel extends Model
{
    protected $table = 'educ_programs_level';
    public function year_level()
    {
        return $this->hasMany(EducYearLevel::class, 'program_level_id', 'id');
    }
}
