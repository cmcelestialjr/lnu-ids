<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducDiscountOption extends Model
{
    protected $table = 'educ_discount_option';
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
