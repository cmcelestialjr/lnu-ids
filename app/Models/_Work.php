<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Work extends Model
{
    protected $table = '_work';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo(UsersRole::class, 'role_id', 'id');
    }
    public function position()
    {
        return $this->belongsTo(HRPosition::class, 'position_id', 'id');
    }
    public function emp_stat()
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_stat_id', 'id');
    }
    public function fund_source()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'id');
    }
    public function fund_service()
    {
        return $this->belongsTo(FundServices::class, 'fund_services_id', 'id');
    }
    public function designation()
    {
        return $this->belongsTo(HRDesignation::class, 'designation_id', 'id');
    }
    public function type()
    {
        return $this->belongsTo(_WorkType::class, 'type_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
    public function pt_option()
    {
        return $this->belongsTo(HRPTOption::class, 'pt_option_id', 'id');
    }
}
