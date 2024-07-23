<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDTRInfo extends Model
{
    protected $table = 'users_dtr_info';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
    public function user_id_no()
    {
        return $this->belongsTo(Users::class, 'id_no', 'id_no')->withDefault();
    }
    public function option()
    {
        return $this->belongsTo(UsersSchedTimeOption::class, 'option_id', 'id')->withDefault();
    }
}
