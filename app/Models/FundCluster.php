<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundCluster extends Model
{
    protected $table = 'fund_cluster';
    public function fund_source()
    {
        return $this->hasMany(FundSource::class, 'fund_cluster_id', 'id');
    }
    public function fund_financing()
    {
        return $this->belongsTo(FundFinancing::class, 'financing_id', 'id');
    }
    public function updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
