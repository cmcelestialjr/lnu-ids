<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDTRType extends Model
{
    protected $table = 'users_dtr_type';
    public function user_id()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function dtr_type_id()
    {
        return $this->belongsTo(DTRType::class, 'dtr_type_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
