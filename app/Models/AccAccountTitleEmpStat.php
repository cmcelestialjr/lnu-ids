<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountTitleEmpStat extends Model
{
    protected $table = 'acc_account_title_emp_stat';
    public function account_title_payroll()
    {
        return $this->belongsTo(AccAccountTitlePayroll::class, 'account_title_payroll_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
