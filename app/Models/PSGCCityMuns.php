<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSGCCityMuns extends Model
{
    protected $table = 'psgc_city_muns';
    public function provinces()
    {
        return $this->belongsTo(PSGCProvinces::class, 'province_uacs', 'uacs')->withDefault();
    }
    public function brgys()
    {
        return $this->hasMany(PSGCBrgys::class, 'city_mun_uacs', 'uacs');
    }
}

?>
