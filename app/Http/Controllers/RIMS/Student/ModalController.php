<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function studentViewModal(Request $request){
        $id = $request->id;
        $query = StudentsInfo::where('user_id',$id)->first();
        $program_level = StudentsProgram::where('user_id',$id)
                ->select('program_level_id','curriculum_id')
                ->orderBy('program_level_id','DESC')->first();
        $data = array(
            'id' => $id,
            'query' => $query,
            'program_level' => $program_level->program_level_id,
            'curriculum' => $program_level->curriculum_id
        );
        return view('rims/student/studentViewModal',$data);
    }
    public function studentTORModal(Request $request){
        $id = $request->id;
        $program_level_ids = StudentsProgram::where('user_id',$id)
                ->select('program_level_id')
                ->groupBy('program_level_id')
                ->pluck('program_level_id')->toArray();
        $query = EducProgramLevel::whereIn('id',$program_level_ids)->orderBy('id','DESC')->get();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentTORModal',$data);
    }
    public function studentCurriculumModal(Request $request){
        $id = $request->id;
        $program_level_ids = StudentsProgram::where('user_id',$id)
            ->where('curriculum_id','<>',NULL)
            ->select('program_level_id')
            ->groupBy('program_level_id')
            ->pluck('program_level_id')->toArray();
        $query = EducProgramLevel::whereIn('id',$program_level_ids)->orderBy('id','DESC')->get();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentCurriculumModal',$data);
    }
    public function studentCoursesModal(Request $request){
        $id = $request->id;
        $school_year_id = $request->school_year_id;
        $query = EducOfferedSchoolYear::where('id',$school_year_id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentCoursesModal',$data);
    }
}