<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRBilling extends Model
{
    protected $table = 'hr_billing';
    public function list()
    {
        return $this->hasMany(HRBillingList::class, 'billing_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo(HRDeductionGroup::class, 'group_id', 'id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
