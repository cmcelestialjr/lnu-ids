<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsEnrollmentForm extends Model
{
    protected $table = 'students_enrollment_form';
    public function info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
