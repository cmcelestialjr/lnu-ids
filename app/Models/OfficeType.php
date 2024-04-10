<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeType extends Model
{
    protected $table = 'office_type';

    public function office()
    {
        return $this->hasMany(Office::class, 'office_type_id', 'id');
    }
}
