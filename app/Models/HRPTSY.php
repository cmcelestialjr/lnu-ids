<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPTSY extends Model
{
    protected $table = 'hr_pt_sy';
    public function list()
    {
        return $this->hasMany(HRPT::class, 'pty_sy_id', 'id');
    }
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
