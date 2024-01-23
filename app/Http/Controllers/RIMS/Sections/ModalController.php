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
use DateInterval;
use DatePeriod;
use DateTime;

class ModalController extends Controller
{
    public function sectionViewModal(Request $request){
        $id = $request->id;
        $level = $request->level;
        $query = EducOfferedCourses::with('curriculum.curriculum',
                                          'curriculum.offered_program.program',
                                          'course.grade_level')
                        ->where('offered_curriculum_id',$id)
                        ->where('year_level',$level)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/sections/sectionViewModal',$data);
    }
    public function sectionNewModal(Request $request){
        $id = $request->id;
        $branch_id = $request->branch_id;
        $program_id = $request->program_id;
        
        $grade_level = EducYearLevel::
                whereHas('courses', function ($subQuery) use ($id,$program_id,$branch_id) {
                    $subQuery->whereHas('courses.curriculum.offered_program', function ($subQuery) use ($id,$program_id,$branch_id) {
                        $subQuery->where('school_year_id', $id);
                        $subQuery->where('program_id', $program_id);
                        $subQuery->where('branch_id', $branch_id);
                    });
                })
                ->get();
        
        $query = EducOfferedPrograms::with('program')
            ->where('school_year_id',$id)
            ->where('program_id',$program_id)
            ->where('branch_id',$branch_id)
            ->first();
        $curriculum = EducOfferedCurriculum::with('curriculum')
                    ->whereHas('offered_program', function ($subQuery) use ($id,$program_id,$branch_id) {
                        $subQuery->where('school_year_id', $id);
                        $subQuery->where('program_id', $program_id);
                        $subQuery->where('branch_id', $branch_id);
                    })->get()
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
    public function courseViewModal(Request $request){
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)->first();
        $data = array(
            'query' => $query
        );
        return view('rims/sections/courseViewModal',$data);
    }    
    public function minMaxModal(Request $request){
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)->first();
        $data = array(
            'query' => $query,
            'id' => $id
        );
        return view('rims/sections/minMaxModal',$data);
    }
}