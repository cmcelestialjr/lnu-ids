<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollBank extends Model
{
    protected $table = 'hr_payroll_bank';

    protected $fillable = [
        'payroll_id',
        'type',
        'remarks',
        'updated_by'
    ];
    public function payroll()
    {
        return $this->belongsTo(HRPayroll::class, 'payroll_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
