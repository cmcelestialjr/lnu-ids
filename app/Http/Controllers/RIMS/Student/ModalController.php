<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCurriculum;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\StudentsTORpurpose;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModalController extends Controller
{
    public function studentViewModal(Request $request){
        $id = $request->id;
        $program_level_id = NULL;
        $curriculum_id = NULL;
        $query = Users::with('personal_info.country',
                             'personal_info.religion',
                             'personal_info.civil_statuses',
                             'personal_info.res_brgy',
                             'personal_info.res_city_muns',
                             'personal_info.res_province',
                             'personal_info.per_brgy',
                             'personal_info.per_city_muns',
                             'personal_info.per_province',
                             'student_info.program.departments',
                             'student_info.curriculum',
                             'student_info.grade_level',
                             'education.level',
                             'education.school',
                             'education.program',
                             'family.fam_relation')
            ->where('id',$id)
            ->first();
        $program_level = StudentsProgram::where('user_id',$id)
                ->select('program_level_id','curriculum_id')
                ->orderBy('program_level_id','DESC')->first();
        if($program_level){
            $program_level_id = $program_level->program_level_id;
            $curriculum_id = $program_level->curriculum_id;
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'program_level' => $program_level_id,
            'curriculum' => $curriculum_id
        );
        return view('rims/student/studentViewModal',$data);
    }
    public function studentTORModal(Request $request){
        $id = $request->id;
        $program_level_ids = StudentsCourses::where('user_id',$id)
                ->select('program_level_id')
                ->groupBy('program_level_id')
                ->pluck('program_level_id')->toArray();
        $query = EducProgramLevel::whereIn('id',$program_level_ids)->orderBy('id','DESC')->get();
        $student = Users::find($id);
        $data = array(
            'id' => $id,
            'query' => $query,
            'student' => $student
        );
        return view('rims/student/studentTORModal',$data);
    }
    public function studentCurriculumModal(Request $request){
        $id = $request->id;
        $program_level_ids = StudentsProgram::where('user_id',$id)
            // ->where('curriculum_id','<>',NULL)
            ->select('program_level_id')
            ->groupBy('program_level_id')
            ->pluck('program_level_id')->toArray();
        $query = EducProgramLevel::whereIn('id',$program_level_ids)->orderBy('id','DESC')->get();
        $student = Users::find($id);
        $data = array(
            'id' => $id,
            'query' => $query,
            'student' => $student
        );
        return view('rims/student/studentCurriculumModal',$data);
    }
    public function studentCoursesModal(Request $request){
        $id = $request->id;
        $school_year_id = $request->school_year_id;
        $query = EducOfferedSchoolYear::where('id',$school_year_id)->first();
        $lab_units = StudentsCourses::where('user_id',$id)
            ->where('school_year_id',$school_year_id)->sum('lab_units');
        $data = array(
            'id' => $id,
            'query' => $query,
            'lab_units' => $lab_units
        );
        return view('rims/student/studentCoursesModal',$data);
    }
    public function studentCourseAddModal(Request $request){
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $id = $request->id;
        $level = $request->level;
        $query = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$level)
            ->orderBy('year_from','DESC')->get();
        $level = EducProgramLevel::find($level);
        $period = $level->period;
        $periods = EducGradePeriod::where('period',$period)->get();
        $statuses = StudentsCourseStatus::get();
        $data = array(
            'id' => $id,
            'query' => $query,
            'periods' => $periods,
            'period' => $period,
            'statuses' => $statuses
        );
        return view('rims/student/studentCourseAddModal',$data);
    }
    public function studentCourseAddTr(Request $request){
        $length = $request->length;
        $statuses = StudentsCourseStatus::get();
        $data = array(
            'length' => $length,
            'statuses' => $statuses
        );
        return view('rims/student/studentCourseAddTr',$data);
    }
    public function studentShiftModal(Request $request){
        $id = $request->id;
        $query = StudentsInfo::where('user_id',$id)->first();
        $branch = EducBranch::get();
        $programs = EducPrograms::where('id','!=',$query->program_id)
            ->where('program_level_id',$query->program_level_id)
            ->where('status_id',1)
            ->orderBy('id','ASC')
            ->get();
        $data = array(
            'query' => $query,
            'programs' => $programs,
            'branch' => $branch
        );
        return view('rims/student/studentShiftModal',$data);
    }
    public function studentPrintModal(Request $request){
        $id = $request->id;
        $level = $request->level;
        $query = EducProgramLevel::where('id',$level)->first();
        $student = Users::find($id);
        $purpose = StudentsTORpurpose::get();
        $data = array(
            'id' => $id,
            'query' => $query,
            'student' => $student,
            'purpose' => $purpose
        );
        return view('rims/student/studentPrintModal',$data);
    }
}
