<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTRlogsCopy extends Model
{
    protected $table = 'dtr_logs_copy';
    public function user()
    {
        return $this->belongsTo(Users::class, 'id_no', 'id_no')->withDefault();
    }
}
