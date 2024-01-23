<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDeduction extends Model
{
    protected $table = 'hr_deduction';
    protected $fillable = [
        'group_id', 
        'name', 
        'percent', 
        'percent_employer', 
        'ceiling', 
        'updated_by'
    ];
    public function emp_stat()
    {
        return $this->hasMany(HRadgEmpStat::class, 'deduction_id', 'id')->orderBy('emp_stat_id');
    }
    public function payroll_type()
    {
        return $this->hasMany(HRadgPayrollType::class, 'deduction_id', 'id')->orderBy('payroll_type_id');
    }
    public function employee()
    {
        return $this->hasMany(HRDeductionEmployee::class, 'deduction_id', 'id');
    }
    public function payroll_deduction()
    {
        return $this->hasMany(HRPayrollDeduction::class, 'deduction_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo(HRDeductionGroup::class, 'group_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
