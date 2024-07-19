<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPTOption extends Model
{
    protected $table = 'hr_pt_option';
    public function _work()
    {
        return $this->hasMany(_Work::class, 'pt_option_id', 'id');
    }
    public function pt()
    {
        return $this->hasMany(HRPT::class, 'pt_option_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
