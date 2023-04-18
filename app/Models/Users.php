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
    public function user_role()
    {
        return $this->hasMany(UsersRoleList::class, 'user_id', 'id');
    }
}
