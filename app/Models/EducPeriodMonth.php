<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducPeriodMonth extends Model
{
    protected $table = 'educ_period_month';
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id')->withDefault();
    }
}
