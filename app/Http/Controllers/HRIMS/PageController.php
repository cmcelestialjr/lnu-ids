<?php

namespace App\Http\Controllers\HRIMS;
use App\Http\Controllers\Controller;
use App\Jobs\DevicesCheckJob;
use App\Models\_WorkType;
use App\Models\AccAccountTitle;
use App\Models\CivilStatuses;
use App\Models\Devices;
use App\Models\DTRlogsCopy;
use App\Models\DTRType;
use App\Models\EmploymentStatus;
use App\Models\FundServices;
use App\Models\FundSource;
use App\Models\HRCreditType;
use App\Models\HRDeductionGroup;
use App\Models\HRPayrollDuration;
use App\Models\HRPayrollOption;
use App\Models\HRPayrollType;
use App\Models\HRPositionType;
use App\Models\Ludong\Student\LudongStudentInfo;
use App\Models\Sexs;
use App\Models\Signatory;
use App\Models\Status;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersRole;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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
        // try{
        //     $zk = new ZKTeco('10.5.200.16',4370);
        //     $zk1 = new ZKTeco('10.5.200.17',4370);
        //     // $zk_guard = new ZKTeco('10.5.205.8',4370);
        //     // $zk = new ZKTeco('10.5.201.137',4370); //mis
        //     // $zk = new ZKTeco('10.5.205.11',4370); //kabilang guard
        //     // $zk = new ZKTeco('10.5.205.23',4370); //youngfield
        //     // $zk = new ZKTeco('10.5.205.137',4370); // admin
        //     if ($zk->connect() && $zk1->connect()){
        //     //     $zk->setTime(date('y-m-d H:i:s'));
        //     //     $zk->testVoice();
        //     //    $getTime = $zk->getTime();
        //     //         $zk->clearAdmin();
        //     //     $zk_guard->clearAttendance();
        //     //     $attendace = $zk->specificAttendance(230209);
        //     //     $attendace = $zk_guard->getAttendance();
        //     //     $deviceName = $zk_guard->getTime();
        //     //     140720                
        //     //     $zk->setTime('2023-08-24 10:26:15');
        //     //     $getTime = $zk->getTime(); 
        //     //     $getUser = $zk_guard->userSpecific(900911);
        //     //     $startDate = '2023-06-01';
        //     //     $endDate = '2023-06-31';
        //     $attendace = $zk->getAttendance();
        //     //     $attendace = collect($attendace1)->filter(function ($record) use ($startDate, $endDate) {
        //     //         $recordDate = $record['timestamp'];
        //     //         $recordDate = date('Y-m-d', strtotime($recordDate));
        //     //         return $recordDate >= $startDate && $recordDate <= $endDate;
        //     //     });
                
        //     $getUser = $zk->getUser();
        //     //     foreach($attendace as $row){
        //     //         $insert = new DTRlogsCopy();
                
        //     //         $insert->id_no = $row['id'];
        //     //         $insert->state = $row['state'];
        //     //         $insert->dateTime = $row['timestamp'];
        //     //         $insert->type = $row['type'];
        //     //         $insert->save();
        //     //     }
                
        //         //  foreach($getUser as $row){
        //         //     //$getFingerprint = $zk1->getFingerprint($row['uid']);
        //         //     //$query = Users::where('id_no',$row['userid'])->first();
        //         //     $name = 'abc';
        //         //     // if($query!=NULL){
        //         //     //     $name_services = new NameServices;
        //         //     //     $name = $name_services->firstname($query->lastname,$query->firstname,$query->middlename,$query->extname);
        //         //     // }
        //         //     $zk1->setUser($row['uid'],$row['userid'],$name,NULL,0,0);

        //         //     // if ($getFingerprint !== false) {
        //         //     //     $setFinger = $zk->setFingerprint($row['uid'],$getFingerprint);
        //         //     //     $resultFinger = $setFinger;
        //         //     // }else{
        //         //     //     $resultFinger = 'error';
        //         //     // }
        //         // }
                
        //    }
        // }catch(Exception $e){

        // }
        $data['deviceName'] = $deviceName;
        $data['getUser'] = $getUser;
        $data['attendace'] = $attendace;
        $data['getFingerprint'] = $getFingerprint;
        $data['getTime'] = $getTime;
        return view($this->page.'/home',$data);
    }
    public function employees($data){       
        return view($this->page.'/employee/employee1',$data);
    }
    public function new_employee($data){
        $emp_stat = EmploymentStatus::get();
        $fund_source = FundSource::get();
        $fund_services = FundServices::get();
        $credit_type = HRCreditType::get();
        $user_role = UsersRole::where('id','>',1)->get();
        $work_type = _WorkType::get();
        $sex = Sexs::get();
        $civil_status = CivilStatuses::get();
        $data['hr_emp_stat'] = $emp_stat;
        $data['hr_fund_source'] = $fund_source;
        $data['hr_fund_services'] = $fund_services;
        $data['hr_credit_type'] = $credit_type;
        $data['hr_user_role'] = $user_role;
        $data['hr_work_type'] = $work_type;
        $data['hr_sex'] = $sex;
        $data['hr_civil_status'] = $civil_status;
        return view($this->page.'/employee/new',$data);
    }
    // public function employee_deduction($data){
    //     $emp_stat = EmploymentStatus::get();
    //     $fund_source = FundSource::get();
    //     $payroll_type = HRPayrollType::get();
    //     $data['emp_stat'] = $emp_stat;
    //     $data['payroll_type'] = $payroll_type;
    //     $data['fund_source'] = $fund_source;
    //     return view($this->page.'/employee/deduction/deduction',$data); 
    // }

    //payroll
    public function generate($data){
        $emp_stat = EmploymentStatus::get();
        $fund_source = FundSource::get();
        $fund_service = FundServices::get();
        $payroll_type = HRPayrollType::get();
        $payroll_duration = HRPayrollDuration::get();
        $payroll_option = HRPayrollOption::get();
        $account_titles = AccAccountTitle::where('payment','yes')->get();
        $data['emp_stat'] = $emp_stat;
        $data['payroll_type'] = $payroll_type;
        $data['fund_source'] = $fund_source;
        $data['fund_service'] = $fund_service;
        $data['payroll_duration'] = $payroll_duration;
        $data['payroll_option'] = $payroll_option;
        $data['account_titles'] = $account_titles;
        return view($this->page.'/payroll/generate/generate',$data); 
    }
    public function payroll_view($data){
        $payroll_type = HRPayrollType::get();
        $data['payroll_type'] = $payroll_type;
        return view($this->page.'/payroll/view/view',$data); 
    }
    public function payroll_type($data){
        return view($this->page.'/payroll/payrollType/payroll_type',$data); 
    }
    public function deduction($data){
        return view($this->page.'/payroll/deduction/deduction',$data); 
    }
    public function allowance($data){
        return view($this->page.'/payroll/allowance/allowance',$data); 
    }
    public function billing($data){
        $deduction_group = HRDeductionGroup::get();
        $payroll_type = HRPayrollType::get();
        $data['deduction_group'] = $deduction_group;
        $data['payroll_type'] = $payroll_type;
        return view($this->page.'/payroll/billing/billing',$data); 
    }

    public function signatory($data){
        $signatory_type = Signatory::where('system_shorten',strtoupper($this->page))
            ->select('type')
            ->groupBy('type')
            ->get();
        $data['signatory_type'] = $signatory_type;
        return view('signatory/signatory',$data);
    }
    
    public function dtr($data){
        $route = Route::current();
        $routeName = $route->getName();
        $dtrType = DTRType::get();
        $data['dtrType'] = $dtrType;
        $data['routeName'] = $routeName;
        return view($this->page.'/dtr/all1',$data); 
    }
    public function position($data){
        $position_type = HRPositionType::get();
        $position_status = Status::whereHas('status_list', function ($query) {
                $query->where('table','position');
            })->get();
        $data['position_type'] = $position_type;
        $data['position_status'] = $position_status;
        return view($this->page.'/position/position',$data); 
    }

    public function devices($data){        
        return view($this->page.'/devices/devices',$data);
    }

    //my
    public function mydtr($data){
        $user = Auth::user();
        $data['id_no'] = $user->id_no;        
        return view($this->page.'/dtr/individual',$data);
    }
    public function mypayslip($data){
        $user = Auth::user();
        $payroll_type = HRPayrollType::get();
        $data['id_no'] = $user->id_no;
        $data['payroll_type'] = $payroll_type;
        return view($this->page.'/my/payslip',$data);
    }
}
?>