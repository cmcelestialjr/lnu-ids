<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _PersonalInfo extends Model
{
    protected $table = '_personal_info';
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function sexs()
    {
        return $this->belongsTo(Sexs::class, 'sex', 'id');
    }
    public function civil_statuses()
    {
        return $this->belongsTo(CivilStatuses::class, 'civil_status_id', 'id');
    }
    public function blood()
    {
        return $this->belongsTo(BloodType::class, 'blood_type_id', 'id');
    }
    public function res_brgy()
    {
        return $this->belongsTo(PSGCBrgys::class, 'res_brgy_id', 'id');
    }
    public function res_city_muns()
    {
        return $this->belongsTo(PSGCCityMuns::class, 'res_municipality_id', 'id');
    }
    public function res_province()
    {
        return $this->belongsTo(PSGCProvinces::class, 'res_province_id', 'id');
    }
    public function per_brgy()
    {
        return $this->belongsTo(PSGCBrgys::class, 'per_brgy_id', 'id');
    }
    public function per_city_muns()
    {
        return $this->belongsTo(PSGCCityMuns::class, 'per_municipality_id', 'id');
    }
    public function per_province()
    {
        return $this->belongsTo(PSGCProvinces::class, 'per_province_id', 'id');
    }
    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
