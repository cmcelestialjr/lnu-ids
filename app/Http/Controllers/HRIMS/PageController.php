<?php

namespace App\Http\Controllers\HRIMS;
use App\Http\Controllers\Controller;
use App\Models\_WorkType;
use App\Models\CivilStatuses;
use App\Models\DTRlogsCopy;
use App\Models\EmploymentStatus;
use App\Models\FundSource;
use App\Models\HRCreditType;
use App\Models\HRPositionStatus;
use App\Models\HRPositionType;
use App\Models\Ludong\Student\LudongStudentInfo;
use App\Models\Sexs;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersRole;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rats\Zkteco\Lib\ZKTeco;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'hrims';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        $getUser = [];
        $attendace = [];
        $deviceName = '';
        $getFingerprint = '';
        $resultFinger = '';
        $getTime = '';
        try{
            //$zk = new ZKTeco('10.5.205.181',4370);
            $zk_guard = new ZKTeco('10.5.205.8',4370);
            $zk = new ZKTeco('10.5.201.137',4370); //office
           // $zk = new ZKTeco('10.5.205.11',4370); //kabilang guard
          // $zk = new ZKTeco('10.5.205.23',4370); //office
            if ($zk->connect() && $zk_guard->connect()){
                //$zk->setTime(date('y-m-d H:i:s'));
                //$zk->testVoice();
               // $getTime = $zk->getTime();
                    //$zk->clearAdmin();
                // $zk_guard->clearAttendance();
                // $attendace = $zk->specificAttendance(230209);
                // $attendace = $zk_guard->getAttendance();
                // $deviceName = $zk_guard->getTime();
                //$getUser = $zk->userSpecific(220806);
                $startDate = '2023-06-01';
                $endDate = '2023-06-31';
                 $attendace = $zk->getAttendance();
                // $attendace = collect($attendace1)->filter(function ($record) use ($startDate, $endDate) {
                //     $recordDate = $record['timestamp'];
                //     $recordDate = date('Y-m-d', strtotime($recordDate));
                //     return $recordDate >= $startDate && $recordDate <= $endDate;
                // });
                
               // $getUser = $zk->getUser();
                //$getUser = $zk->getUser();
                // foreach($attendace as $row){
                //     $insert = new DTRlogsCopy();
                //     $insert->id_no = $row['id'];
                //     $insert->state = $row['state'];
                //     $insert->dateTime = $row['timestamp'];
                //     $insert->type = $row['type'];
                //     $insert->save();
                // }
                
                // foreach($getUser as $row){
                //     //$getFingerprint = $zk1->getFingerprint($row['uid']);
                //     $query = Users::where('id_no',$row['userid'])->first();
                //     $name = $row['userid'];
                //     if($query!=NULL){
                //         $name_services = new NameServices;
                //         $name = $name_services->firstname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                //     }
                //     $zk->setUser($row['uid'],$row['userid'],$name,NULL,0,0);

                //     // if ($getFingerprint !== false) {
                //     //     $setFinger = $zk->setFingerprint($row['uid'],$getFingerprint);
                //     //     $resultFinger = $setFinger;
                //     // }else{
                //     //     $resultFinger = 'error';
                //     // }
                // }
                
            }
        }catch(Exception $e){

        }
        $data['deviceName'] = $deviceName;
        $data['getUser'] = $getUser;
        $data['attendace'] = $attendace;
        $data['getFingerprint'] = $getFingerprint;
        return view($this->page.'/home',$data);
    }
    public function employees($data){
        return view($this->page.'/employee/employee1',$data);
    }
    public function new_employee($data){
        $emp_stat = EmploymentStatus::get();
        $fund_source = FundSource::get();
        $credit_type = HRCreditType::get();
        $user_role = UsersRole::where('id','>',1)->get();
        $work_type = _WorkType::get();
        $sex = Sexs::get();
        $civil_status = CivilStatuses::get();
        $data['hr_emp_stat'] = $emp_stat;
        $data['hr_fund_source'] = $fund_source;
        $data['hr_credit_type'] = $credit_type;
        $data['hr_user_role'] = $user_role;
        $data['hr_work_type'] = $work_type;
        $data['hr_sex'] = $sex;
        $data['hr_civil_status'] = $civil_status;
        return view($this->page.'/employee/new',$data);
    }

    public function generate($data){
        return view($this->page.'/deduction/deduction',$data); 
    }
    public function payroll_view($data){
        return view($this->page.'/deduction/deduction',$data); 
    }
    public function deduction($data){
        return view($this->page.'/deduction/deduction',$data); 
    }
    
    public function dtr($data){
        return view($this->page.'/dtr/all1',$data); 
    }
    public function position($data){
        $position_type = HRPositionType::get();
        $position_status = HRPositionStatus::get();
        $data['position_type'] = $position_type;
        $data['position_status'] = $position_status;
        return view($this->page.'/position/position',$data); 
    }

    //my
    public function mydtr($data){
        $user = Auth::user();
        $data['id_no'] = $user->id_no;
        return view($this->page.'/dtr/individual',$data);
    }
}
?>