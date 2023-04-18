<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _PersonalInfo extends Model
{
    protected $table = '_personal_info';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
