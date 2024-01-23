<?php

namespace App\Models\Poes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoesProfile extends Model
{
    protected $connection = 'cpanel';
    protected $table = 'tbl_profile';
    public function program()
    {
        return $this->hasMany(PoesProgramEnrollment::class, 'profile_id', 'profile_id');
    }
}
