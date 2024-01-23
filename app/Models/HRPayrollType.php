<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollType extends Model
{
    protected $table = 'hr_payroll_type';
    
    public function guideline()
    {
        return $this->hasMany(HRPayrollTypeGuideline::class, 'payroll_type_id', 'id');
    }    
    public function time_period()
    {
        return $this->belongsTo(HRTimePeriod::class, 'time_period_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
