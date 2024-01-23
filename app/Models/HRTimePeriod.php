<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRTimePeriod extends Model
{
    protected $table = 'hr_time_period';
    public function payroll_type()
    {
        return $this->hasMany(HRPayrollType::class, 'time_period_id', 'id');
    }
    public function qtr_sem()
    {
        return $this->hasMany(HRTimePeriodQtrSem::class, 'time_period_id', 'id');
    }
}
