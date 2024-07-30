<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSchedTimeOption extends Model
{
    protected $table = 'users_sched_time_option';
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
    public function sched_time()
    {
        return $this->hasMany(UsersSchedTime::class, 'option_id', 'id');
    }
    public function days()
    {
        return $this->hasMany(UsersSchedDays::class, 'user_time_id', 'id');
    }
}
