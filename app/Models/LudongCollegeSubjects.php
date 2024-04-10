<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongCollegeSubjects extends Model
{
    protected $table = 'ludong_college_subjects';
    public function mark()
    {
        return $this->hasMany(LudongMark::class, 'catalog_no', 'catalog_no');
    }
}
