<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongStudentFamily extends Model
{
    protected $connection = 'student';
    protected $table = 'family';
    public function student()
    {
        return $this->belongsTo(LudongStudents::class, 'stud_id', 'stud_id');
    }
}
