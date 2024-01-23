<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedDiscount extends Model
{
    protected $table = 'educ__offered_discount';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function discount()
    {
        return $this->belongsTo(EducDiscount::class, 'discount_id', 'id');
    }
    public function option()
    {
        return $this->belongsTo(EducDiscountOption::class, 'option_id', 'id');
    }
    public function list()
    {
        return $this->hasMany(EducOfferedDiscountList::class, 'offered_discount_id', 'id');
    }
    public function fees_type()
    {
        return $this->hasMany(EducOfferedDiscountFeesType::class, 'offered_discount_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
