<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongCollegeSubjectsExt extends Model
{
    protected $table = 'ludong_college_subjects_ext';
    public function mark()
    {
        return $this->hasMany(LudongMark::class, 'catalog_id', 'catalog_id');
    }
}
