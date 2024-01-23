<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';
    public function updated_by_id()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
