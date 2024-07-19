<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPTMonths extends Model
{
    protected $table = 'hr_pt_months';
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function pt()
    {
        return $this->belongsTo(HRPT::class, 'pt_id', 'id');
    }
    public function sy()
    {
        return $this->belongsTo(HRPTSY::class, 'pty_sy_id', 'id');
    }
    public function pt_option()
    {
        return $this->belongsTo(HRPTOption::class, 'pt_option_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
