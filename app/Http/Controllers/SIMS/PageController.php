<?php

namespace App\Http\Controllers\SIMS;
use App\Http\Controllers\Controller;
use App\Models\_EducationBg;
use App\Models\_FamilyBg;
use App\Models\EducCurriculum;
use App\Models\EducDepartments;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    private $student_user_id;
    public function __construct()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $this->page = 'sims';
        $this->validate = new ValidateAccessServices;
        $this->student_user_id = $user_id;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function information($data){
        $user_id = $this->student_user_id;

        $info = Users::with('personal_info.sexs',
                            'personal_info.religion',
                            'personal_info.civil_statuses')
            ->where('id',$user_id)->first();

        $education_level = EducProgramLevel::with(['education_bg' => function ($query) use ($user_id){
                $query->where('user_id',$user_id);
            }])
            ->whereHas('education_bg', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('id','DESC')->get();

        $program_level = EducProgramLevel::whereHas('student_programs', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('id','DESC')->get();

        $family_bg = _FamilyBg::with('fam_relation')
            ->where('user_id',$user_id)
            ->orderBy('relation_id','ASC')
            ->get();
        
        $data['info'] = $info;
        $data['education_level'] = $education_level;
        $data['program_level'] = $program_level;
        $data['family_bg'] = $family_bg;
        
        return view($this->page.'/information/information',$data);
    }
    public function students($data){        
        return view($this->page.'/students/students',$data);
    }
    public function teachers($data){
        $user_id = $this->student_user_id;

        $program_level = EducProgramLevel::whereHas('students_courses', function ($query) use ($user_id) {
            $query->where('user_id',$user_id);
        })->orderBy('id','DESC')->get();

        $school_year = EducOfferedSchoolYear::with('grade_period')
            ->whereHas('student_courses', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('year_from','DESC')
            ->orderBy('grade_period_id','DESC')
            ->get()->map(function($query)  {
                return [
                    'id' => $query->id,
                    'name' => $query->year_from.'-'.$query->year_to.' '.$query->grade_period->name_no
                ];
            })->toArray();

        $data['program_level'] = $program_level;
        $data['school_year'] = $school_year;

        return view($this->page.'/teachers/teachers',$data);
    }
    public function list($data){
        $user_id = $this->student_user_id;
        $course_status = StudentsCourseStatus::whereHas('students', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->get();
        $program_level = EducProgramLevel::whereHas('students_courses', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->get();

        $data['course_status'] = $course_status;
        $data['program_level'] = $program_level;
        
        return view($this->page.'/courses/list',$data);
    }
    public function schedule($data){
        $user_id = $this->student_user_id;

        $school_year = EducOfferedSchoolYear::with('grade_period','student_courses')
            ->whereHas('student_courses', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('year_from','DESC')
            ->orderBy('grade_period_id','DESC')
            ->get()->map(function($query)  {
                foreach($query->student_courses as $row){
                    $program_level = $row->program_level->name;
                }
                return [
                    'id' => $query->id,
                    'name' => $query->year_from.'-'.$query->year_to.' '.$query->grade_period->name_no
                ];
            })->toArray();

        $data['school_year'] = $school_year;
        return view($this->page.'/courses/schedule',$data);
    }
    public function pre_enroll($data){
        $user_id = $this->student_user_id;
        $school_year = EducOfferedSchoolYear::orderBy('grade_period_id','DESC')->orderBy('grade_period_id','DESC')->first();
        $student_info = StudentsInfo::with('program', 'program_code', 'grade_level')->where('user_id',$user_id)->first();        
        $program_id = $student_info->program_id;
        $program_code_id = $student_info->program_code_id;
        $curriculum_id = $student_info->curriculum_id;
        $school_year_id = $school_year->id;
        $sections = EducOfferedCourses::
            whereHas('curriculum', function ($query) use ($program_id,$program_code_id,$curriculum_id,$school_year_id) {
                $query->where('curriculum_id',$curriculum_id);
                $query->whereHas('offered_program', function ($query) use ($program_id,$program_code_id,$school_year_id) {
                    $query->where('program_id',$program_id);
                    $query->where('program_code_id',$program_code_id);
                    $query->where('school_year_id',$school_year_id);
                });
            })
            ->select('section')
            ->groupBy('section')
            ->orderBy('section','ASC')
            ->get();
        if($student_info->curriculum_id==NULL){
            $curriculum = EducCurriculum::orderBy('year_from','DESC')->first();
        }else{
            $curriculum = EducCurriculum::where('id',$student_info->curriculum_id)->first();
        }
        if($school_year->enrollment_extension==NULL || $school_year->enrollment_extension<$school_year->enrollment_to){
            $enrollment_date = $school_year->enrollment_to;
        }else{
            $enrollment_date = $school_year->enrollment_extension;
        }
        $data['school_year'] = $school_year;
        $data['student_info'] = $student_info;
        $data['curriculum'] = $curriculum;
        $data['sections'] = $sections;
        $data['enrollment_date'] = $enrollment_date;
        $data['student_id'] = $user_id;

        return view($this->page.'/pre_enroll/pre_enroll',$data);
    }
    // public function grades($data){
    //     $level_ids = array(1,2,3); 
    //     $validate = $this->validate->check($data,$level_ids);
    //     if($validate=='success'){
    //         return view($this->page.'/home',$data);
    //     }else{
    //         return view('layouts/error/404');
    //     }
    // }
}
?>