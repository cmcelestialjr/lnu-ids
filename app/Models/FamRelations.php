<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamRelations extends Model
{
    protected $table = 'fam_relations';
    public function family_bg()
    {
        return $this->hasMany(_FamilyBg::class, 'relation_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
