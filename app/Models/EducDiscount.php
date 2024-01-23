<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDiscount extends Model
{
    protected $table = 'educ_discount';
    public function list()
    {
        return $this->hasMany(EducDiscountList::class, 'discount_id', 'id');
    }
    public function fees_type()
    {
        return $this->hasMany(EducDiscountFeesType::class, 'discount_id', 'id');
    }
    public function option()
    {
        return $this->belongsTo(EducDiscountOption::class, 'option_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo(HRPositionStatus::class, 'status_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
