<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongStudents extends Model
{
    protected $connection = 'student';
    protected $table = 'info';
    public function grade()
    {
        return $this->hasMany(LudongGradeLog::class, 'stud_id', 'stud_id');
    }
    public function course()
    {
        return $this->hasMany(LudongStudentCourse::class, 'stud_id', 'stud_id');
    }
    public function classes()
    {
        return $this->hasMany(LudongStudentClasses::class, 'stud_id', 'stud_id');
    }
    public function contact()
    {
        return $this->hasMany(LudongStudentContact::class, 'stud_id', 'stud_id');
    }
    public function phone()
    {
        return $this->hasMany(LudongStudentPhone::class, 'stud_id', 'stud_id');
    }
    public function family()
    {
        return $this->hasMany(LudongStudentFamily::class, 'stud_id', 'stud_id');
    }
}
