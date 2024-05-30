<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _LearningType extends Model
{
    protected $table = '_learning_type';
    public function learning()
    {
        return $this->hasMany(_Learning::class, 'type_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
