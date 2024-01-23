<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundFinancing extends Model
{
    protected $table = 'fund_financing';
    public function fund_cluster()
    {
        return $this->hasMany(FundCluster::class, 'financing_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
