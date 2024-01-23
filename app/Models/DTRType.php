<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTRType extends Model
{
    protected $table = 'dtr_type';
    public function type()
    {
        return $this->hasMany(UsersDTRType::class, 'dtr_type_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
