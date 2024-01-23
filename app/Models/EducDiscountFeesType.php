<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDiscountFeesType extends Model
{
    protected $table = 'educ_discount_fees_type';
    protected $fillable = [
        'discount_id', 
        'fees_type_id',
        'updated_by',
        'updated_at',
        'created_at'
    ];
    public function discount()
    {
        return $this->belongsTo(EducDiscount::class, 'discount_id', 'id');
    }
    public function fees_type()
    {
        return $this->belongsTo(EducFeesType::class, 'fees_type_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
