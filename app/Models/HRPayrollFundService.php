<?php

namespace App\Models;

use App\Models\HRPayrollList as ModelsHRPayrollList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollFundService extends Model
{
    protected $table = 'hr_payroll_fund_service';
    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }
    public function fund_service()
    {
        return $this->belongsTo(FundServices::class, 'fund_service_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
