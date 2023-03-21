<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedPrograms;
use App\Models\EducYearLevel;
use Illuminate\Http\Request;

class LoadViewController extends Controller
{
    public function programsSelect(Request $request){
        $id = $request->id;
        $query = EducOfferedPrograms::with('department','program')->where('school_year_id',$id)->get();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/sections/programsSelect',$data);
    }
    public function gradeLevelSelect(Request $request){
        $id = $request->id;
        $offered_courses_ids = EducOfferedCourses::where('offered_curriculum_id',$id)->pluck('course_id')->toArray();
        $courses_grade_level_id = EducCourses::whereIn('id',$offered_courses_ids)->pluck('grade_level_id')->toArray();
        $grade_level = EducYearLevel::whereIn('id',$courses_grade_level_id)->get();
        $data = array(
            'grade_level' => $grade_level
        );
        return view('rims/sections/gradeLevelSelect',$data);
    }
}