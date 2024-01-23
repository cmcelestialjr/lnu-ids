<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _EducationBg extends Model
{
    protected $table = '_education_bg';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'level_id', 'id');
    }
    public function school_id()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
    public function program()
    {
        return $this->belongsTo(EducProgramsAll::class, 'program_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
