<?php

namespace App\Http\Controllers\RIMS\SchoolYear;
use App\Http\Controllers\Controller;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use Illuminate\Http\Request;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;

class ModalController extends Controller
{
    public function programs(Request $request){
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/schoolYear/modalPrograms',$data);
    }
    public function editView(Request $request){
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/schoolYear/modalEdit',$data);
    }
    public function programsViewModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('rims/schoolYear/programsViewModal',$data);
    }
    public function coursesOpenModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $programs = EducProgramsCode::with('program')->orderBy('program_id','ASC')->get();
        $data = array(
            'id' => $id,
            'programs' => $programs,
            'user_access_level' => $user_access_level
        );
        return view('rims/schoolYear/coursesOpenModal',$data);
    }
    public function coursesViewModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $program = EducOfferedPrograms::with('program')->where('id',$id)->first();
        $curriculums = EducOfferedCurriculum::with('curriculum')->where('offered_program_id',$id)->get()
                        ->sortByDesc(function($query, $key) {
                            return $query->curriculum->year_from;
                        });
        $data = array(
            'id' => $id,
            'program' => $program,
            'curriculums' => $curriculums,
            'user_access_level' => $user_access_level
        );
        return view('rims/schoolYear/coursesViewModal',$data);
    }
    public function courseViewStatusModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $course = EducOfferedCourses::with('course')->where('id',$id)->first();
        $statuses = EducCourseStatus::get();
        $data = array(
            'id' => $id,
            'course' => $course,
            'statuses' => $statuses,
            'user_access_level' => $user_access_level
        );
        return view('rims/schoolYear/courseViewStatusModal',$data);
    }
}