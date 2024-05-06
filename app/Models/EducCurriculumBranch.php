<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCurriculumBranch extends Model
{
    protected $table = 'educ_curriculum_branch';
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function branch()
    {
        return $this->belongsTo(EducBranch::class, 'branch_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'status_id', 'id')->withDefault();
    }
}
