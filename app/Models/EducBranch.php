<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducBranch extends Model
{
    protected $table = 'educ_branch';
    public function program()
    {
        return $this->hasMany(EducProgramsCode::class, 'branch_id', 'id');
    }
    public function programs_offered()
    {
        return $this->hasMany(EducOfferedPrograms::class, 'branch_id', 'id');
    }
}
