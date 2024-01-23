<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    protected $table = 'signatory';
    public function signatory()
    {
        return $this->belongsTo(Users::class, 'signatory_id', 'id')->withDefault();
    }
    public function updated_by_id()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}
