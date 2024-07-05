<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRPT extends Model
{
    protected $table = 'hr_pt';
    public function sy()
    {
        return $this->belongsTo(HRPTSY::class, 'pty_sy_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
