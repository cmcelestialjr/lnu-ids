<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRBillingList extends Model
{
    protected $table = 'hr_billing_list';
    public function billing()
    {
        return $this->belongsTo(HRBilling::class, 'billing_id', 'id');
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
