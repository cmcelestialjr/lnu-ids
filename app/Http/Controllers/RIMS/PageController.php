<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use App\Models\DTRlogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;
use App\Models\EducPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducGradePeriod;
use App\Models\EducCourseStatus;
use App\Models\EducProgramLevel;
use App\Models\Ludong\LudongGradeLog;
use App\Models\Ludong\LudongStudents;
use App\Models\StudentsProgram;
//use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use Rats\Zkteco\Lib\ZKTeco;
use Intervention\Image\Facades\Image;
use Exception;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'rims';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        $user_details123 = [];
        $attendace = [];
        try{
            //$zk = new ZKTeco('10.5.205.8',4370);
            //$zk = new ZKTeco('10.5.205.181',4370);
            //if ($zk->connect()){
                    //$zk->clearAdmin();
                    //$zk->setUser(5,5,'cesar','',1);
                    //$user_details123 = $zk->specificUser(5);
                    //$zk->clearAttendance(5);
                //$attendace = $zk->getAttendance();
                // foreach($attendace as $row){
                //     $insert = new DTRlogs();
                //     $insert->user_id = $row['id'];
                //     $insert->state = $row['state'];
                //     $insert->dateTime = $row['timestamp'];
                //     $insert->type = $row['type'];
                //     $insert->save();
                // }
                    //$attendace = $zk->getAttendanceSpecific();
                // $zk->setUser(1,230209,'Celestial, Cesar Jr. M.',NULL,0,0);
            //}
        }catch(Exception $e){

        }
        $data['user_details123'] = $user_details123;
        $data['attendace'] = $attendace;
        return view($this->page.'/home',$data);
    }
    public function students($data){
        $data['program_level'] = EducProgramLevel::get();
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        $data['date_graduate'] = StudentsProgram::select('date_graduate')->orderBy('date_graduate','DESC')->groupBy('date_graduate')->get();
        return view($this->page.'/student/student',$data);
    }
    public function departments($data){   
        return view($this->page.'/departments/departments',$data);
    }
    public function programs($data){   
        $data['statuses'] = EducCourseStatus::get();
        return view($this->page.'/programs/programs',$data);
    }
    public function courses_list($data){        
        return view($this->page.'/courses_list',$data);
    }
    public function sections($data){        
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/sections/sections',$data);
    }
    public function school_year($data){        
        $data['grade_period'] = EducGradePeriod::get();
        return view($this->page.'/schoolYear/school_year',$data);
    }
    public function enrollment($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/enrollment/enrollment',$data);
    }
    public function schedule($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/schedule/schedule',$data);
    }
    public function student_ludong($data){
        $data['ludong_year'] = LudongGradeLog::select('sy')
            ->where('sy','<=',3000)
            ->where('sy','>=',1900)
            ->orderBy('sy','DESC')->groupBY('sy')->get();
        return view($this->page.'/ludong/student_ludong',$data);
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