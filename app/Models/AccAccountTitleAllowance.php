<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountTitleAllowance extends Model
{
    protected $table = 'acc_account_title_allowance';
    public function account_title()
    {
        return $this->belongsTo(AccAccountTitle::class, 'account_title_id', 'id');
    }
    public function allowance()
    {
        return $this->belongsTo(HRAllowance::class, 'allowance_id', 'id');
    }
    public function info_updated_by()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
