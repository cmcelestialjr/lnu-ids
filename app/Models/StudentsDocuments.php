<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsDocuments extends Model
{
    protected $table = 'students_documents';
    public function list()
    {
        return $this->hasMany(StudentsDocumentsList::class, 'document_id', 'id');
    }
}
