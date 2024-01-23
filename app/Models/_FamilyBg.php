<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _FamilyBg extends Model
{
    protected $table = '_family_bg';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function fam_relation()
    {
        return $this->belongsTo(FamRelations::class, 'relation_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
