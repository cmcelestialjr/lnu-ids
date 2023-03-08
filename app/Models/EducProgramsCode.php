<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducProgramsCode extends Model
{
    protected $table = 'educ_programs_code';
    public function program()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id')->withDefault();
    }
}
