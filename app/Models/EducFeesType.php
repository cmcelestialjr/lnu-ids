<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducFeesType extends Model
{
    protected $table = 'educ_fees_type';
    public function fees()
    {
        return $this->hasMany(EducFees::class, 'type_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
