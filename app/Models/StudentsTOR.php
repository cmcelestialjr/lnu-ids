<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsTOR extends Model
{
    protected $table = 'students_tor';
    public function reason()
    {
        return $this->belongsTo(StudentsTORreason::class, 'purpose_id', 'id')->withDefault();
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
