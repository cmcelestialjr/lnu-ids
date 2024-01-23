<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDeductionGroup extends Model
{
    protected $table = 'hr_deduction_group';
    public function emp_stat()
    {
        return $this->hasMany(HRadgEmpStat::class, 'group_id', 'id');
    }
    public function payroll_type()
    {
        return $this->hasMany(HRadgPayrollType::class, 'group_id', 'id');
    }
    public function deduction()
    {
        return $this->hasMany(HRDeduction::class, 'group_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
