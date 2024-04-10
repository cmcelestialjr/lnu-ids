<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';

    public function office_type()
    {
        return $this->belongsTo(OfficeType::class, 'office_type_id', 'id')->withDefault();
    }
    public function office_parent()
    {
        return $this->belongsTo(Office::class, 'parent_office_id', 'id')->withDefault();
    }
    public function designation()
    {
        return $this->hasOne(HRDesignation::class, 'office_id', 'id')->withDefault();
    }
}
