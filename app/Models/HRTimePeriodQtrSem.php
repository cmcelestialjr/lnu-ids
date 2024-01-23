<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRTimePeriodQtrSem extends Model
{
    protected $table = 'hr_time_period_qtr_sem';
    public function time_period()
    {
        return $this->belongsTo(HRTimePeriod::class, 'time_period_id', 'id');
    }
}
