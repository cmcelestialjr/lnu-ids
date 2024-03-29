<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSGCProvinces extends Model
{
    protected $table = 'psgc_provinces';
    public function regions()
    {
        return $this->belongsTo(PSGCRegions::class, 'region_uacs', 'uacs')->withDefault();
    }
    public function city_muns()
    {
        return $this->hasMany(PSGCCityMuns::class, 'province_uacs', 'uacs');
    }
}

?>
