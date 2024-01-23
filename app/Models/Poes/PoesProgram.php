<?php

namespace App\Models\Poes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoesProgram extends Model
{
    protected $connection = 'cpanel';
    protected $table = 'tbl_programs';
    public function program()
    {
        return $this->hasMany(PoesProgramEnrollment::class, 'program_id', 'program_id');
    }
}
