<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id_no',
        'stud_id',
        'lastname',
        'firstname',
        'middlename',
        'extname',
        'remember_token',
        'image',
        'honorific',
        'post_nominal',
        'status_id',
        'emp_status_id',
        'user_id',
        'designated',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->withDefault();
    }
    public function personal_info()
    {
        return $this->belongsTo(_PersonalInfo::class, 'id', 'user_id');
    }
    public function employee_info()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('role_id',2)->orderBy('date_from','ASC');
    }
    public function instructor_info()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('role_id',3)->orderBy('date_from','ASC');
    }
    public function employee_default()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->orderBy('status','DESC')->orderBy('emp_stat_id','DESC')->orderBy('date_from','ASC');
    }
    public function employee_gov_y()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('gov_service','Y')->orderBy('status','DESC')->orderBy('emp_stat_id','DESC')->orderBy('date_from','ASC');
    }
    public function employee_gov_n()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('gov_service','N')->orderBy('status','DESC')->orderBy('emp_stat_id','DESC')->orderBy('date_from','ASC');
    }
    public function student_info()
    {
        return $this->belongsTo(StudentsInfo::class, 'id', 'user_id');
    }
    public function student_program()
    {
        return $this->hasMany(StudentsProgram::class, 'id', 'user_id');
    }
    public function student_program_latest()
    {
        return $this->belongsTo(StudentsProgram::class, 'id', 'user_id')->orderBy('year_from','DESC')->limit(1);
    }
    public function date_entry()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('position_id','>',0)->orderBy('date_from','DESC');
    }
    public function education()
    {
        return $this->hasMany(_EducationBg::class, 'user_id', 'id')->orderBy('level_id','DESC')->orderBy('period_from','DESC');
    }
    public function family()
    {
        return $this->hasMany(_FamilyBg::class, 'user_id', 'id')->orderBy('relation_id','ASC')->orderBy('dob','DESC');
    }
    public function work()
    {
        return $this->hasMany(_Work::class, 'user_id', 'id');
    }
    public function part_time()
    {
        return $this->hasMany(HRPT::class, 'user_id', 'id');
    }
    public function courses()
    {
        return $this->hasMany(EducOfferedCourses::class, 'instructor_id', 'id');
    }
    public function payrolls()
    {
        return $this->hasMany(HRPayrollList::class, 'user_id', 'id');
    }
    public function user_role()
    {
        return $this->hasMany(UsersRoleList::class, 'user_id', 'id');
    }
    public function dtr()
    {
        return $this->hasMany(UsersDTR::class, 'id_no', 'id_no');
    }
    public function dtr_track()
    {
        return $this->hasMany(UsersDTRTrack::class, 'id_no', 'id_no');
    }
    public function systems()
    {
        return $this->hasMany(UsersSystems::class, 'user_id', 'id');
    }
    public function nav()
    {
        return $this->hasMany(UsersSystemsNav::class, 'user_id', 'id');
    }
    public function nav_sub()
    {
        return $this->hasMany(UsersSystemsNavSub::class, 'user_id', 'id');
    }
    public function deduction()
    {
        return $this->hasMany(HRDeductionEmployee::class, 'user_id', 'id');
    }
}
