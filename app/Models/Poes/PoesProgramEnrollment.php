<?php

namespace App\Models\Poes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoesProgramEnrollment extends Model
{
    protected $connection = 'cpanel';
    protected $table = 'tbl_program_enrollment';
    public function profile_info()
    {
        return $this->belongsTo(PoesProfile::class, 'profile_id', 'profile_id');
    }
    public function program_info()
    {
        return $this->belongsTo(PoesProgram::class, 'program_id', 'program_id');
    }
    public function sy_sem_info()
    {
        return $this->belongsTo(PoesSySem::class, 'sy_sem_id', 'sy_sem_id');
    }
}
