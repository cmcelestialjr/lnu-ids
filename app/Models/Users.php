<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->withDefault();
    }
    public function student_info()
    {
        return $this->belongsTo(StudentsInfo::class, 'id', 'user_id');
    }    
    public function personal_info()
    {
        return $this->belongsTo(_PersonalInfo::class, 'id', 'user_id');
    }
    public function employee_info()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('role_id',2)->orderBy('date_from','DESC');
    }
    public function instructor_info()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->where('role_id',3)->orderBy('date_from','DESC');
    }
    public function employee_default()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->orderBy('emp_stat_id','ASC')->orderBy('date_from','DESC');
    }
    public function date_entry()
    {
        return $this->belongsTo(_Work::class, 'id', 'user_id')->orderBy('date_from','ASC');
    }
    public function work()
    {
        return $this->hasMany(_Work::class, 'user_id', 'id');
    }
    public function courses()
    {
        return $this->hasMany(EducOfferedCourses::class, 'instructor_id', 'id');
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
}
