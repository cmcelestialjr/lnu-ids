<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducProgramLevel;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducPrograms;
use App\Models\EducYearLevel;

class ModalController extends Controller
{
    public function sectionNewModal(Request $request){
        $id = $request->id;
        $program_id = $request->program_id;
        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$program_id)->pluck('curriculum_id')->toArray();
        $curriculum = EducCurriculum::whereIn('id',$offered_curriculum_ids)->orderBy('year_from','DESC')->first();
        $curriculum_id = $curriculum->id;
        $offered_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_id)->where('curriculum_id',$curriculum_id)->first();
        $offered_curriculum_id = $offered_curriculum->id;
        $offered_courses_ids = EducOfferedCourses::where('offered_curriculum_id',$offered_curriculum_id)->pluck('course_id')->toArray();
        $courses_grade_level_id = EducCourses::whereIn('id',$offered_courses_ids)->pluck('grade_level_id')->toArray();
        $grade_level = EducYearLevel::whereIn('id',$courses_grade_level_id)->get();
        $query = EducOfferedPrograms::with('program')->where('id',$program_id)->first();
        $curriculum = EducOfferedCurriculum::with('curriculum')->where('offered_program_id',$program_id)->get()
                            ->sortByDesc(function($query, $key) {
                                return $query->curriculum->year_from;
                            });
        $data = array(
            'query' => $query,
            'grade_level' => $grade_level,
            'curriculum' => $curriculum
        );
        return view('rims/sections/sectionNewModal',$data);
    }
}