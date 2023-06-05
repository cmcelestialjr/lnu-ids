<?php

namespace App\Models\Ludong;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LudongSubjects extends Model
{
    protected $table = 'ludong_subjects';
    public function grade()
    {
        return $this->hasMany(LudongGradeLog::class, 'catalog_no', 'catalog_no');
    }
}
