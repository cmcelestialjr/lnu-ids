<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $table = 'religion';

    protected $fillable = [
        'name',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->hasMany(_PersonalInfo::class, 'religion_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
