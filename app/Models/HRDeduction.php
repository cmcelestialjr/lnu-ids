<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDeduction extends Model
{
    protected $table = 'hr_deduction';
    public function group()
    {
        return $this->belongsTo(HRDeductionGroup::class, 'group_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
