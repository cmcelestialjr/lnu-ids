<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollDeduction extends Model
{
    protected $table = 'hr_payroll_deduction';

    protected $fillable = [
        'payroll_list_id', 
        'payroll_id', 
        'user_id',
        'deduction_id',        
        'amount',
        'percent',
        'percent_employer',
        'ceiling',
        'updated_by',
        'updated_at',
        'created_at'
    ];
    
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
    public function deduction()
    {
        return $this->belongsTo(HRDeduction::class, 'deduction_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
