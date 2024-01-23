<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollEmpStat extends Model
{
    protected $table = 'hr_payroll_emp_stat';
    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function get_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
