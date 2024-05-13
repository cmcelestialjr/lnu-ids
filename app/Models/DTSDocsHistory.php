<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTSDocsHistory extends Model
{
    protected $table = 'dts_docs_history';
    public function doc()
    {
        return $this->belongsTo(DTSDocs::class, 'doc_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function option()
    {
        return $this->belongsTo(DTSOption::class, 'option_id', 'id');
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
