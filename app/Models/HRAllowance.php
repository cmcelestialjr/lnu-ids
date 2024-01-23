<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRAllowance extends Model
{
    protected $table = 'hr_allowance';
    public function emp_stat()
    {
        return $this->hasMany(HRadgEmpStat::class, 'allowance_id', 'id');
    }
    public function payroll_type()
    {
        return $this->hasMany(HRadgPayrollType::class, 'allowance_id', 'id');
    }
    public function payroll_allowance()
    {
        return $this->hasMany(HRPayrollAllowance::class, 'allowance_id', 'id')->orderBY('allowance_id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
