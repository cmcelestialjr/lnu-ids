<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongMark extends Model
{
    protected $table = 'ludong_mark';
    public function student()
    {
        return $this->belongsTo(Users::class, 'stud_id', 'stud_id');
    }
    public function subject()
    {
        return $this->belongsTo(LudongCollegeSubjects::class, 'catalog_no', 'catalog_no');
    }
    public function subject_ext()
    {
        return $this->belongsTo(LudongCollegeSubjects::class, 'catalog_id', 'catalog_id');
    }
    public function school_name()
    {
        return $this->belongsTo(LudongSchools::class, 'school', 'school_id');
    }
}
