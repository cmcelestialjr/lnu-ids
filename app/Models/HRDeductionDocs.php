<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDeductionDocs extends Model
{
    protected $table = 'hr_deduction_docs';
    protected $fillable = [
        'deduction_employee_id', 
        'amount', 
        'date_from',
        'date_to',        
        'doc',
        'remarks',
        'updated_by'
    ];
    public function employee()
    {
        return $this->belongsTo(HRDeductionEmployee::class, 'deduction_employee_id', 'id');
    }
    public function get_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
