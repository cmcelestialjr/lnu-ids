<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingType extends Model
{
    protected $table = 'tracking_type';
    
    public function tracking()
    {
        return $this->hasMany(Tracking::class, 'tracking_id', 'id');
    }
}
