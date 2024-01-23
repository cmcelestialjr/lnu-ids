<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';
    
    public function tracking_type()
    {
        return $this->belongsTo(TrackingType::class, 'tracking_type_id', 'id')->withDefault();
    }
    public function payroll()
    {
        return $this->hasMany(HRPayroll::class, 'tracking_id', 'tracking_id');
    }


}
