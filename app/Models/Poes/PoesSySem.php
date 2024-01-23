<?php
namespace App\Models\Poes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoesSySem extends Model
{
    protected $connection = 'cpanel';
    protected $table = 'tbl_sy_sem';
    public function program()
    {
        return $this->hasMany(PoesProgramEnrollment::class, 'sy_sem_id', 'sy_sem_id');
    }
}
