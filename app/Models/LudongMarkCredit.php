<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongMarkCredit extends Model
{
    protected $table = 'ludong_mark_credit';
    public function school()
    {
        return $this->belongsTo(LudongSchools::class, 'school', 'school_id');
    }
}
