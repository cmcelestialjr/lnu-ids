<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducOfferedDiscountList extends Model
{
    protected $table = 'educ__offered_discount_list';
    public function school_year()
    {
        return $this->belongsTo(EducOfferedSchoolYear::class, 'school_year_id', 'id')->withDefault();
    }
    public function discount()
    {
        return $this->belongsTo(EducOfferedDiscount::class, 'offered_discount_id', 'id');
    }
    public function program()
    {
        return $this->belongsTo(EducPrograms::class, 'program_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
