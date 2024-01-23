<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountTitle extends Model
{
    protected $table = 'acc_account_title';
    public function payroll_type()
    {
        return $this->hasMany(AccAccountTitlePayroll::class, 'account_title_id', 'id');
    }
    public function deduction()
    {
        return $this->hasMany(AccAccountTitleDeduction::class, 'account_title_id', 'id');
    }
    public function allowance()
    {
        return $this->hasMany(AccAccountTitleAllowance::class, 'account_title_id', 'id');
    }
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
