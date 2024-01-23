<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollList extends Model
{
    protected $table = 'hr_payroll_list';    

    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }    
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function fund_source()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'id');
    }
    public function fund_services()
    {
        return $this->belongsTo(FundServices::class, 'fund_services_id', 'id');
    }
    public function months()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_list_id', 'id')->where('option','default');
    }
    public function unclaimeds()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_list_id', 'id')->where('option','unclaimed');
    }
    public function month_unclaimed()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_list_id', 'id');
    }
    public function allowance()
    {
        return $this->hasMany(HRPayrollAllowance::class, 'payroll_list_id', 'id')->orderBy('allowance_id');
    }
    public function deductions()
    {
        return $this->hasMany(HRPayrollDeduction::class, 'payroll_list_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
