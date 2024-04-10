<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPosition extends Model
{
    protected $table = 'hr_position';
    public function designation()
    {
        return $this->belongsTo(HRDesignation::class, 'designation_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function fund_source()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo(UsersRole::class, 'role_id', 'id');
    }
    public function type()
    {
        return $this->belongsTo(HRPositionType::class, 'type_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function office_designate()
    {
        return $this->belongsTo(HRDesignation::class, 'office_id', 'office_id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
