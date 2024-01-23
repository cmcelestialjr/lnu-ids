<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongStudentClasses extends Model
{
    protected $connection = 'student';
    protected $table = 'classes';
    public function student()
    {
        return $this->belongsTo(LudongStudents::class, 'stud_id', 'stud_id');
    }
}
