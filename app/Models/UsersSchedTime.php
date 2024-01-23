<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSchedTime extends Model
{
    protected $table = 'users_sched_time';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function option()
    {
        return $this->belongsTo(UsersSchedTimeOption::class, 'option_id', 'id')->withDefault();
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
    public function days()
    {
        return $this->hasMany(UsersSchedDays::class, 'user_time_id', 'id');
    }
}
