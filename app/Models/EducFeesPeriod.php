<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducFeesPeriod extends Model
{
    protected $table = 'educ_fees_period';
    public function fees_list()
    {
        return $this->belongsTo(EducFeesList::class, 'fees_list_id', 'id')->withDefault();
    }
    public function fees()
    {
        return $this->belongsTo(EducFees::class, 'fees_id', 'id')->withDefault();
    }
    public function branch()
    {
        return $this->belongsTo(EducBranch::class, 'branch_id', 'id')->withDefault();
    }
    public function program_level()
    {
        return $this->belongsTo(EducProgramLevel::class, 'program_level_id', 'id')->withDefault();
    }
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id')->withDefault();
    }
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
