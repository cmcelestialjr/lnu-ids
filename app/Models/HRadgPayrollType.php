<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRadgPayrollType extends Model
{
    protected $table = 'hr_adg_payroll_type';
    public function allowance()
    {
        return $this->belongsTo(HRAllowance::class, 'allowance_id', 'id');
    }
    public function deduction()
    {
        return $this->belongsTo(HRDeduction::class, 'deduction_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo(HRDeductionGroup::class, 'group_id', 'id');
    }
    public function payroll_type()
    {
        return $this->belongsTo(HRPayrollType::class, 'payroll_type_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
