<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPayrollTypeGuideline extends Model
{
    protected $table = 'hr_payroll_type_guideline'; 

    public function payroll_type()
    {
        return $this->belongsTo(HRPayrollType::class, 'payroll_type_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
