<?php

namespace App\Models;

use App\Models\HRPayrollList as ModelsHRPayrollList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollFundSource extends Model
{
    protected $table = 'hr_payroll_fund_source';
    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }
    public function fund_source()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
