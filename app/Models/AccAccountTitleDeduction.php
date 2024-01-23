<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountTitleDeduction extends Model
{
    protected $table = 'acc_account_title_deduction';
    public function account_title()
    {
        return $this->belongsTo(AccAccountTitle::class, 'account_title_id', 'id');
    }
    public function deduction()
    {
        return $this->belongsTo(HRDeduction::class, 'deduction_id', 'id');
    }
    public function payroll_deductions()
    {
        return $this->hasMany(HRPayrollDeduction::class, 'deduction_id', 'deduction_id');
    }
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
