<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _WorkType extends Model
{
    protected $table = '_work_type';
    public function work()
    {
        return $this->hasMany(_Work::class, 'course_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
