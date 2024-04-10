<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDesignation extends Model
{
    protected $table = 'hr_designation';
    public function current_user()
    {
        return $this->belongsTo(Users::class, 'current_user_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo(UsersRole::class, 'role_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
