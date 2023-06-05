<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSchedDays extends Model
{
    protected $table = 'users_sched_days';
    public function time()
    {
        return $this->belongsTo(UsersSchedTime::class, 'user_time_id', 'id')->withDefault();
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
