<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducYearLevel;
use App\Models\EducCourseStatus;
use App\Models\EducCoursesType;
use App\Models\EducDepartments;
use App\Models\EducGradePeriod;
use App\Models\EducLabCourses;
use App\Models\EducLabGroup;
use App\Models\EducProgramLevel;
use App\Models\EducProgramsCode;
use Exception;

class ModalController extends Controller
{
    public function newCourse(Request $request){
        $id = $request->id;
        $curriculum = EducCurriculum::with('programs','status')->where('id',$id)->first();
        $year_level = EducYearLevel::where('program_level_id',$curriculum->programs->program_level_id)->orderBy('level','ASC')->get();
        $grade_period = EducGradePeriod::get();
        $course_type = EducCoursesType::get();
        $courses = EducCourses::with('grade_period','grade_level')->where('curriculum_id',$id)
                        ->orderBy('grade_level_id','ASC')
                        ->orderBy('grade_period_id','ASC')->get();
        $lab_group = EducLabGroup::get();
        $data = array(
            'id' => $id,
            'curriculum' => $curriculum,
            'year_level' => $year_level,
            'grade_period' => $grade_period,
            'courses' => $courses,
            'lab_group' => $lab_group,
            'course_type' => $course_type
        );
        return view('rims/programs/newCourseModal',$data);
    }
    public function courseUpdate(Request $request){
        $id = $request->id;
        $query = EducCourses::with('grade_level','grade_period')->where('id', $id)->first();
        $lab_group_course_get = EducLabCourses::where('course_code',$query->code)->first();
        $lab_group = EducLabGroup::get();
        $course_type = EducCoursesType::get();
        $lab_group_course = '';
        if($lab_group_course_get!=NULL){
            $lab_group_course = $lab_group_course_get->lab_group_id;
        }
        
        $data = array(
            'id' => $id,
            'query' => $query,
            'lab_group' => $lab_group,
            'lab_group_course' => $lab_group_course,
            'course_type' => $course_type
        );
        return view('rims/programs/courseUpdateModal',$data);
    }
    public function curriculumNewModal(Request $request){
        $id = $request->id;
        $query = EducPrograms::where('id', $id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/programs/curriculumNewModal',$data);
    }
    public function programCodesModal(Request $request){
        $id = $request->id;
        $program = EducPrograms::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'program' => $program
        );
        return view('rims/programs/programCodesModal',$data);
    }
    public function programCodeNewModal(Request $request){
        $id = $request->id;
        $data = array(
            'id' => $id
        );
        return view('rims/programs/programCodeNewModal',$data);
    }
    public function programCodeEditModal(Request $request){
        $id = $request->id;
        $query = EducProgramsCode::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/programs/programCodeEditModal',$data);
    }
}