<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongGradeLog extends Model
{
    protected $table = 'ludong_grade_log';
    public function info()
    {
        return $this->belongsTo(LudongStudents::class, 'stud_id', 'stud_id');
    }
    public function subject()
    {
        return $this->belongsTo(LudongSubjects::class, 'catalog_no', 'catalog_no');
    }
}
