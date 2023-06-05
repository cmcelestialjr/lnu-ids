<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    protected $table = 'holidays';
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
