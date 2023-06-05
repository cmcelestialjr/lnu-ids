<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSGCBrgys extends Model
{
    protected $table = 'psgc_brgys';
    public function city_muns()
    {
        return $this->belongsTo(PSGCCityMuns::class, 'city_mun_uacs', 'uacs')->withDefault();
    }
}

?>
