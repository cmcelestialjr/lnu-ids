<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusList extends Model
{
    protected $table = 'status_list';
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->withDefault();
    }
}
