<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDay extends Model
{
    protected $table = 'educ_day';
    public function user()
    {
        return $this->belongsTo(EducCourseStatus::class, 'user_id', 'id')->withDefault();
    }
}
