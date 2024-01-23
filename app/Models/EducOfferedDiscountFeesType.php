<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedDiscountFeesType extends Model
{
    protected $table = 'educ__offered_discount_fees_type';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function discount()
    {
        return $this->belongsTo(EducOfferedDiscount::class, 'offered_discount_id', 'id');
    }
    public function fees_type()
    {
        return $this->belongsTo(EducFeesType::class, 'fees_type_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
