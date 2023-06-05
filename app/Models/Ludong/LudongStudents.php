<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongStudents extends Model
{
    protected $table = 'ludong_student_info';
    public function grade()
    {
        return $this->hasMany(LudongGradeLog::class, 'stud_id', 'stud_id');
    }
}
