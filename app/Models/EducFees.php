<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducFees extends Model
{
    protected $table = 'educ_fees';
    public function type()
    {
        return $this->belongsTo(EducFeesType::class, 'type_id', 'id')->withDefault();
    }
    public function list()
    {
        return $this->hasMany(EducFeesList::class, 'fees_id', 'id');
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
