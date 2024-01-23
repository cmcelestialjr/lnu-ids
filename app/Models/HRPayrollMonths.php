<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollMonths extends Model
{
    protected $table = 'hr_payroll_months';
    public function list()
    {
        return $this->belongsTo(HRPayrollList::class, 'payroll_list_id', 'id');
    }
    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
