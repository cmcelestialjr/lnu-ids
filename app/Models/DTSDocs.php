<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTSDocs extends Model
{
    protected $table = 'dts_docs';
    public function type()
    {
        return $this->belongsTo(DTSType::class, 'type_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo(DTSStatus::class, 'status_id', 'id');
    }
    public function history()
    {
        return $this->hasMany(DTSDocsHistory::class, 'doc_id', 'id');
    }
    public function latest()
    {
        return $this->belongsTo(DTSDocsHistory::class, 'id', 'doc_id')->orderBy('created_at','ASC');
    }
    public function created_by_info()
    {
        return $this->belongsTo(Users::class, 'created_by', 'id');
    }
    public function updated_by_info()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id');
    }
}
