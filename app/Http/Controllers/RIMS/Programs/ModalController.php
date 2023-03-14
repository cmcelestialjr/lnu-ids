<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducYearLevel;
use App\Models\EducCourseStatus;
use App\Models\EducGradePeriod;
use Exception;

class ModalController extends Controller
{
    public function viewModal(Request $request){
        $id = $request->id;
        $user_access_level = $request->session()->get('user_access_level');
        $program = EducPrograms::with('codes','departments','program_level','status')->where('id',$id)->first();
        $curriculum = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->first();
        $curriculums = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->get();
        $status = EducCourseStatus::get();
        $year_level = EducYearLevel::where('program_level_id',$program->program_level_id)->orderBy('level','ASC')->get();
        $data = array(
            'id' => $id,
            'program' => $program,
            'curriculum' => $curriculum,
            'curriculums' => $curriculums,
            'year_level' => $year_level,
            'status' => $status,
            'user_access_level' => $user_access_level
        );
        return view('rims/programs/viewModal',$data);
    }
    public function newCourse(Request $request){
        $id = $request->id;
        $curriculum = EducCurriculum::with('programs','status')->where('id',$id)->first();
        $year_level = EducYearLevel::where('program_level_id',$curriculum->programs->program_level_id)->orderBy('level','ASC')->get();
        $grade_period = EducGradePeriod::get();
        $courses = EducCourses::with('grade_period','grade_level')->where('curriculum_id',$id)
                        ->orderBy('grade_level_id','ASC')
                        ->orderBy('grade_period_id','ASC')->get();
        $data = array(
            'id' => $id,
            'curriculum' => $curriculum,
            'year_level' => $year_level,
            'grade_period' => $grade_period,
            'courses' => $courses
        );
        return view('rims/programs/newCourseModal',$data);
    }
    public function programStatusModal(Request $request){
        $id = $request->id;
        $query = EducPrograms::where('id',$id)->first();
        if($query->status_id==1){
            $class = 'danger';
            $btn = 'success';
            $status = 'Closed';
        }else{
            $class = 'success';
            $btn = 'danger';
            $status = 'Open';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'class' => $class,
            'status' => $status,
            'btn' => $btn
        );
        return view('rims/programs/programStatusModal',$data);
    }
    public function courseUpdate(Request $request){
        $id = $request->id;
        $query = EducCourses::with('grade_level','grade_period')->where('id', $id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
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
}