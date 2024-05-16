<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayroll extends Model
{
    protected $table = 'hr_payroll';
    public function payroll_type()
    {
        return $this->belongsTo(HRPayrollType::class, 'payroll_type_id', 'id');
    }
    public function account_title()
    {
        return $this->belongsTo(AccAccountTitle::class, 'account_title_id', 'id');
    }
    public function list()
    {
        return $this->hasMany(HRPayrollList::class, 'payroll_id', 'id');
    }
    public function deduction()
    {
        return $this->hasMany(HRPayrollDeduction::class, 'payroll_id', 'id');
    }
    public function allowance()
    {
        return $this->hasMany(HRPayrollAllowance::class, 'payroll_id', 'id');
    }
    public function emp_stat()
    {
        return $this->hasMany(HRPayrollEmpStat::class, 'payroll_id', 'id');
    }
    public function fund_source()
    {
        return $this->hasMany(HRPayrollFundSource::class, 'payroll_id', 'id');
    }
    public function fund_service()
    {
        return $this->hasMany(HRPayrollFundService::class, 'payroll_id', 'id');
    }
    public function months()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_id', 'id')->where('option','default');
    }
    public function unclaimeds()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_id', 'id')->where('option','unclaimed');
    }
    public function month_unclaimed()
    {
        return $this->hasMany(HRPayrollMonths::class, 'payroll_id', 'id');
    }
    public function tracking()
    {
        return $this->belongsTo(DTSDocs::class, 'tracking_id', 'id');
    }
    public function get_generated_by()
    {
        return $this->belongsTo(Users::class, 'generated_by', 'id');
    }
    public function get_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
