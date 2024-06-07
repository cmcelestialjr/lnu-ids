<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDeductionEmployee extends Model
{
    protected $table = 'hr_deduction_employee';
    protected $fillable = [
        'user_id',
        'deduction_id',
        'payroll_type_id',
        'emp_stat_id',
        'amount',
        'amount_employer',
        'percent',
        'percent_employer',
        'ceiling',
        'date_from',
        'date_to',
        'remarks',
        'updated_by'
    ];
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function deduction()
    {
        return $this->belongsTo(HRDeduction::class, 'deduction_id', 'id');
    }
    public function docs()
    {
        return $this->hasMany(HRDeductionDocs::class, 'deduction_employee_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
