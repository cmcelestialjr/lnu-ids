<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSGCRegions extends Model
{
    protected $table = 'psgc_regions';
    public function provinces()
    {
        return $this->hasMany(PSGCProvinces::class, 'uacs', 'uacs');
    }
}

?>
