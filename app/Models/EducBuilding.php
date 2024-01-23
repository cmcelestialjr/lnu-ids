<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducBuilding extends Model
{
    protected $table = 'educ_buildings';
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->withDefault();
    }
    public function rooms()
    {
        return $this->hasMany(EducRoom::class, 'building_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }
}