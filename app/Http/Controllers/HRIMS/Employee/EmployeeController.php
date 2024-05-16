<?php

namespace App\Http\Controllers\HRIMS\Employee;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
class EmployeeController extends Controller
{
    public function employeeTable(Request $request){
        return $this->table($request);
    }
    public function employeeStat(Request $request){
        return $this->stat($request);
    }
    public function employeeView(Request $request){
        return $this->viewModal($request);
    }
    public function uploadImage(Request $request){
        return $this->uploadImageSubmit($request);
    }
    public function employeeInformation(Request $request){
        return $this->employeeInformationModal($request);
    }
    public function employeeNewSubmit(Request $request){
        return $this->_employeeNewSubmit($request);
    }
    private function table($request){
        $data = array();
        $name_services = new NameServices;
        $option = $request->option;
        $status = $request->status;

        $query = Users::with('employee_info.emp_stat','instructor_info.emp_stat','employee_default.emp_stat')
            ->whereHas('user_role', function ($query) use ($option,$status) {
                if($option=='all'){
                    $query->where('role_id','>',1);
                }else{
                    $query->where('role_id',$option);
                }
                if($status=='all'){
                    $query->where('emp_status_id',1);
                }elseif($status=='sep'){
                    $query->where('emp_status_id',2);
                }else{
                    $query->where('emp_stat',$status);
                    $query->where('emp_status_id',1);
                }
            })->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')->get()
            ->map(function($query) use ($name_services,$option) {
                $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                $position = '';
                $salary = '';
                $emp_stat = '';
                if($option==2){
                    if(isset($query->employee_info)){
                        $position = $query->employee_info->position_title;
                        $salary = $query->employee_info->salary;
                        $emp_stat = $query->employee_info->emp_stat->name;
                    }
                }elseif($option==3){
                    if(isset($query->instructor_info)){
                        $position = $query->instructor_info->position_title;
                        $salary = $query->instructor_info->salary;
                        $emp_stat = $query->instructor_info->emp_stat->name;
                    }
                }else{
                    if(isset($query->employee_default)){
                        $position = $query->employee_default->position_title;
                        $salary = $query->employee_default->salary;
                        $emp_stat = $query->employee_default->emp_stat->name;
                    }
                }
                return [
                    'id' => $query->id,
                    'name' => $name,
                    'id_no' => $query->id_no,
                    'position' => $position,
                    'salary' => $salary,
                    'emp_stat' => $emp_stat
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['id_no'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['position'];
                $data_list['f5'] = $r['salary'];
                $data_list['f6'] = $r['emp_stat'];
                $data_list['f8'] = '<button class="btn btn-primary btn-primary-scan btn-sm employeeView"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                $data_list['f9'] = '<button class="btn btn-info btn-info-scan btn-sm deduction"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-calculator"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function stat($request){

    }
    private function viewModal($request){
        // $connectionName = 'skyhr';
        // DB::connection($connectionName)->getPdo();
        // $employees = DB::connection($connectionName)->table('dbo.tblEmployees')
        //     ->limit(1)
        //     ->get();
        // $data['employees'] = $employees;
        $user_access_level = $request->session()->get('user_access_level');
        $name_services = new NameServices;
        $id = $request->id;
        $query = Users::with('personal_info.sexs',
                             'personal_info.civil_statuses',
                             'employee_default.emp_stat',
                             'date_entry')
            ->find($id);
        if($query->middlename_in_last=='Y'){
            $name = $name_services->lastname_middlename_last($query->lastname,$query->firstname,$query->middlename,$query->extname);
        }else{
            $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
        }

        if($query->staff_status=='2'){
            $class = 'bg-light-red';
        }else{
            $class = '';
        }

        $data = array(
            'query' => $query,
            'name' => $name,
            'class' => $class,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/employeeViewModal',$data);
    }
    private function employeeInformationModal($request){
        $user_access_level = $request->session()->get('user_access_level');
        $name_services = new NameServices;
        $id = $request->id;
        $query = Users::find($id);
        if($query->middlename_in_last=='Y'){
            $name = $name_services->lastname_middlename_last($query->lastname,$query->firstname,$query->middlename,$query->extname);
        }else{
            $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
        }
        $data = array(
            'query' => $query,
            'name' => $name,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/employeeInformationModal',$data);
    }
    public function uploadImageSubmit($request){
        $id = $request->id;
        $file = $request->file;
        $result = 'error';
        $query = Users::find($id);
        if($query!=NULL){
            $imageExtensions = ['jpg','jpeg','png'];
            $imageExtension = strtolower($file->extension());
            if(in_array($imageExtension, $imageExtensions) ){
                $name = $query->id_no;
                $imageName = $name.'.png';
                $path = 'storage\hrims\employee/'.$query->id_no.'\image/';
                if(!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $img = Image::make($file->path());
                $img->resize(250, 250, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path.'/'.$imageName);

                //$file->move($path, $imageName);

                $data = ['image' => $path.$imageName];
                $update = Users::where('id', $id)
                            ->update($data);
                if($update){
                    $result = 'success';
                }
            }
        }
        $data = array('result' => $result);
        return response()->json($data);
    }
    private function _employeeNewSubmit($request){
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $dob = $request->dob;
        $sex = $request->sex;
        $result = 'error';
        if($sex==2){
            $check = Users::where(function ($query) use ($lastname,$firstname) {
                    $query->where('lastname',$lastname);
                    $query->where('firstname',$firstname);
                })
                ->orWhere(function ($query) use ($middlename,$firstname) {
                    $query->where('middlename',$middlename);
                    $query->where('firstname',$firstname);
                })->get();
        }else{
            $check = Users::where(function ($query) use ($lastname,$firstname) {
                    $query->where('lastname',$lastname);
                    $query->where('firstname',$firstname);
                })->get();
        }
        if($check->count()>0){

        }else{
            $result = $this->_employeeNewSubmitProceed($request);
        }
        $data = array('result' => $result);
        return response()->json($data);
    }
    private function _employeeNewSubmitProceed($request){
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $extname = $request->extname;
        $dob = $request->dob;
        $sex = $request->sex;
        $civil_status = $request->civil_status;
        $email = $request->email;
        $contact_no = $request->contact_no;
        $date_from = $request->date_from;
        $date_to_option = $request->date_to_option;
        $date_to = $request->date_to;
        $position_id = $request->position_id;
        $position_title = $request->position_title;
        $position_shorten = $request->position_shorten;
        $salary = $request->salary;
        $sg = $request->sg;
        $step = $request->step;
        $emp_stat = $request->emp_stat;
        $fund_source = $request->fund_source;
        $fund_services = $request->fund_services;
        $gov_service = $request->gov_service;
        $designation = $request->designation;
        $credit_type = $request->credit_type;
        $role = $request->role;
        $result = 'error';
        return $result;
    }
}
?>
