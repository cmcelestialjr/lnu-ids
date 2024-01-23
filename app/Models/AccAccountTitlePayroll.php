<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountTitlePayroll extends Model
{
    protected $table = 'acc_account_title_payroll';
    public function account_title()
    {
        return $this->belongsTo(AccAccountTitle::class, 'account_title_id', 'id');
    }
    public function payroll_type()
    {
        return $this->belongsTo(HRPayrollType::class, 'payroll_type_id', 'id');
    }
    public function emp_stat()
    {
        return $this->hasMany(AccAccountTitleEmpStat::class, 'account_title_payroll_id', 'id');
    }
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
