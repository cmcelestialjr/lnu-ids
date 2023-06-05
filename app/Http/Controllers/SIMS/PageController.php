<?php

namespace App\Http\Controllers\SIMS;
use App\Http\Controllers\Controller;
use App\Models\EducCurriculum;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'sims';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function profile($data){        
        return view($this->page.'/profile/profile',$data);
    }
    public function students($data){        
        return view($this->page.'/students/students',$data);
    }
    public function teachers($data){
        return view($this->page.'/home',$data);
    }
    public function info($data){
        return view($this->page.'/home',$data);
    }
    public function grades($data){
        return view($this->page.'/home',$data);
    }
    public function schedule($data){
        return view($this->page.'/home',$data);
    }
    public function pre_enroll($data){
        $user = Auth::user();
        $user_id = $user->id;
        $school_year = EducOfferedSchoolYear::orderBy('grade_period_id','DESC')->orderBy('grade_period_id','DESC')->first();
        $student_info = StudentsInfo::where('user_id',$user_id)->first();        
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