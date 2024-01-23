<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducFeesList extends Model
{
    protected $table = 'educ_fees_list';
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
    public function user_updated_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function period()
    {
        return $this->hasMany(EducFeesPeriod::class, 'fees_list_id', 'id');
    }
}
