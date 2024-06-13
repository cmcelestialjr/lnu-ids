<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use App\Models\DTRlogs;
use App\Models\EducBranch;
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
use App\Models\Status;
use App\Models\StudentsCourses;
use App\Models\StudentsProgram;
use App\Models\Users;
//use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use Rats\Zkteco\Lib\ZKTeco;
use Intervention\Image\Facades\Image;
use Exception;
use Illuminate\Support\Facades\DB;

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

        $connectionName = 'sis_student';
        $connectionSIScourses = 'sis_courses';
        $connectionCpanel = 'cpanel';
        $datas = [];
        $students_data = [];
        $query = '';
        try {
           // DB::connection($connectionName)->getPdo();

            // $datas1[] = [
            //     'stud_id' => '2303906',
            //     'enroll_status' => 'New',
            //     'surname' => 'Buscano',
            //     'first_name' => 'Angelika',
            //     'middle_name' => 'L',
            //     'qualifier' => '',
            //     'is_case_sensitive' => 'N',
            //     'source' => 'sias'
            // ];

            // DB::connection($connectionName)->table('info')->insert($datas1);

            // $poes_ids = DB::connection($connectionCpanel)->table('tbl_profile')
            //             ->pluck('student_number')->toArray();
            // $query = DB::connection($connectionName)->table('info')
            //     ->where('source','sias')
            //     ->where('stud_id','>=','2300001')
            //     ->whereRaw('LENGTH(stud_id) = ?', [7])
            //     ->whereNotIn('stud_id',$poes_ids)
            //     ->get();
            // $sis_ids = [];
            // if($query1->count()>0){
            //     foreach($query1 as $r){
            //         $datas[] = [
            //             'student_number' => $r->stud_id,
            //             'firstname' => $r->first_name,
            //             'middlename' => $r->middle_name,
            //             'lastname' => $r->surname,
            //             'extname' => $r->qualifier,
            //             'birthday' => '1990-01-01',
            //             'sex' => '',
            //             'civil_status' => '',
            //             'nationality' => '',
            //             'contact_number' => '',
            //             'email_address' => '',
            //             'zip_code' => '6500',
            //             'home_address' => '',
            //             'guardian_name' => '',
            //             'guardian_contact' => '',
            //             'student_type' => '',
            //             'upload_payment' => '',
            //             'created_at' => date('Y-m-d H:i:s')
            //         ];
            //     }
            //     DB::connection($connectionCpanel)->table('tbl_profile')->insert($datas);
            // }
            // $users_temp_id = Users::where('stud_id','!=',NULL)->pluck('stud_id')->toArray();
            // $query_ = DB::connection($connectionName)->table('info')
            //     ->whereIn('stud_id',$users_temp_id)
            //     ->get();
            // if($query_->count()){
            //     foreach($query_ as $row){
            //         $stud_id = $row->stud_id;
            //         $get_program_student = DB::connection($connectionName)->table('course')
            //             ->where('stud_id',$stud_id)
            //             ->orderBy('sy','DESC')
            //             ->orderBy('terms','DESC')
            //             ->orderBy('term','DESC')
            //             ->first();
            //         // $course_id = $get_program_student->course;
            //         // $get_program = DB::connection($connectionSIScourses)->table('info')
            //         //     ->where('course_id',$course_id)
            //         //     ->first();
            //         // $get_program_curriculum = DB::connection($connectionSIScourses)->table('info')
            //         //     ->where('course_id',$course_id)
            //         //     ->first();
            //     }

            //     // DB::table('students_program')->insert($data_program);
            // }
            // $users = Users::where('stud_id','!=',NULL)->pluck('stud_id')->toArray();
            $users_student = DB::connection($connectionName)->table('info')
                        ->get();
            $students_data = $users_student;
            //$query = "Connection to $connectionName is established.";
            //$query = $query1->count();

        } catch (Exception $e) {
            $query = "Connection to $connectionName failed: " . $e->getMessage();
        }
        $data['query'] = $query;
        $data['students_data'] = $students_data;
        return view($this->page.'/home',$data);
    }
    public function students($data){
        $data['program_level'] = EducProgramLevel::get();
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        $data['date_graduate'] = StudentsProgram::select(DB::raw('YEAR(date_graduate) as year'))->orderBy('year','DESC')->groupBy('year')->get();
        return view($this->page.'/student/student',$data);
    }
    public function departments($data){
        return view($this->page.'/departments/departments',$data);
    }
    public function programs($data){
        $data['statuses'] = EducCourseStatus::get();
        $data['branches'] = EducBranch::get();
        $data['program_levels'] = EducProgramLevel::where('program','w')->get();
        return view($this->page.'/programs/programs',$data);
    }
    public function curriculums($data){
        $data['statuses'] = EducCourseStatus::get();
        $data['programs'] = EducProgramLevel::where('program','w')->get();
        $data['branches'] = EducBranch::get();
        return view($this->page.'/curriculums/curriculums',$data);
    }
    public function buildings($data){
        $data['statuses'] = Status::whereHas('status_list', function ($query) {
                $query->where('table','bldg_rm');
            })->get();
        return view($this->page.'/buildings/buildings',$data);
    }
    public function rooms($data){
        $data['statuses'] = Status::whereHas('status_list', function ($query) {
                $query->where('table','bldg_rm');
            })->get();
        return view($this->page.'/rooms/rooms',$data);
    }
    public function courses_list($data){
        return view($this->page.'/courses_list',$data);
    }
    public function sections($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        $data['branches'] = EducBranch::whereHas('programs_offered', function ($query) { })
            ->get();
        return view($this->page.'/sections/sections',$data);
    }
    public function school_year($data){
        $data['grade_period'] = EducGradePeriod::get();
        return view($this->page.'/schoolYear/school_year',$data);
    }
    public function enrollment($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        $data['program_level'] = EducProgramLevel::get();
        return view($this->page.'/enrollment/enrollment',$data);
    }
    public function schedule($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/schedule/schedule',$data);
    }
    public function add_drop($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        $data['school_year_detail'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->first();
        return view($this->page.'/addDrop/addDrop',$data);
    }
    public function report($data){
        $school_years = [];
        $years = StudentsCourses::select('year_from')
            ->groupBy('year_from')
            ->orderBy('year_from','DESC')
            ->get();
        if($years->count()>0){
            foreach($years as $year){
                $school_years[] = $year->year_from.'-'.$year->year_from+1;
            }
        }
        $data['school_years'] = $school_years;
        return view($this->page.'/report/report',$data);
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
