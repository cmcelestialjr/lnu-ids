<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducCourses extends Model
{
    protected $table = 'educ_courses';
    public function curriculum()
    {
        return $this->belongsTo(EducCurriculum::class, 'curriculum_id', 'id')->withDefault();
    }
    public function grade_level()
    {
        return $this->belongsTo(EducYearLevel::class, 'grade_level_id', 'id')->withDefault();
    }
    public function grade_period()
    {
        return $this->belongsTo(EducGradePeriod::class, 'grade_period_id', 'id')->withDefault();
    }
    public function status()
    {
        return $this->belongsTo(EducCourseStatus::class, 'status_id', 'id')->withDefault();
    }
    public function pre_req()
    {
        return $this->hasMany(EducCoursesPre::class, 'course_id', 'id');
    }
}
