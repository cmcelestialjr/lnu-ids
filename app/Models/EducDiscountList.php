<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDiscountList extends Model
{
    protected $table = 'educ_discount_list';
    protected $fillable = [
        'discount_id', 
        'program_id',
        'user_id',
        'updated_by',
        'updated_at'
    ];
    public function discount()
    {
        return $this->belongsTo(EducDiscount::class, 'discount_id', 'id');
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
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
