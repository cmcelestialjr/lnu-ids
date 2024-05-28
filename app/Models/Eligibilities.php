<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eligibilities extends Model
{
    protected $table = 'eligibilities';
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
