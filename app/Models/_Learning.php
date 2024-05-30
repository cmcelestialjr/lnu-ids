<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Learning extends Model
{
    protected $table = '_learning';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function type()
    {
        return $this->belongsTo(_LearningType::class, 'type_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
