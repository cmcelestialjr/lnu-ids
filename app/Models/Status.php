<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    public function status_list()
    {
        return $this->hasMany(StatusList::class, 'status_id', 'id');
    }
}
