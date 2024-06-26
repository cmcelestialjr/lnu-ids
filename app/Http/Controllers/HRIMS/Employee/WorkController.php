<?php

namespace App\Http\Controllers\HRIMS\Employee;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\_WorkType;
use App\Models\EmploymentStatus;
use App\Models\FundServices;
use App\Models\FundSource;
use App\Models\HRCreditType;
use App\Models\HRDesignation;
use App\Models\HRPosition;
use App\Models\Office;
use App\Models\Users;
use App\Models\UsersRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class WorkController extends Controller
{
    public function workTable(Request $request){
        return $this->table($request);
    }
    public function newModal(Request $request){
        return $this->_newModal($request);
    }
    public function editModal(Request $request){
        return $this->_editModal($request);
    }
    public function positionShortenGet(Request $request){
        return $this->_positionShortenGet($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function editSubmit(Request $request){
        return $this->_editSubmit($request);
    }

    private function table($request){
        $user_access_level = $request->session()->get('user_access_level');

        $data = array();
        $id = $request->id;
        $query = _Work::with('role','emp_stat')
            ->where('user_id',$id)
            ->orderBy('date_from','DESC')->get()
            ->map(function($query)  {
                if($query->date_to=='present'){
                    $date_to = 'present';
                }else{
                    $date_to = date('m/d/Y',strtotime($query->date_to));
                }
                if($query->salary==NULL){
                    $salary = '';
                }else{
                    $salary = number_format($query->salary,2);
                }
                return [
                    'id' => $query->id,
                    'date_from' => date('m/d/Y',strtotime($query->date_from)),
                    'date_to' => $date_to,
                    'position_title' => $query->position_title,
                    'designation_title' => $query->designation_title,
                    'salary' => $salary,
                    'sg' => $query->sg,
                    'step' => $query->step,
                    'type' => $query->role->name,
                    'emp_stat' => $query->emp_stat->name,
                    'office' => $query->office,
                    'lwop' => $query->lwop,
                    'separation' => $query->separation,
                    'cause' => $query->cause,
                    'remarks' => $query->remarks,
                    'docs' => $query->docs
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $data_list['f2'] = '<button class="btn btn-primary btn-primary-scan btn-sm workEditModal"
                                            data-id="'.$r['id'].'">
                                            '.$r['date_from'].'
                                        </button>';
                }else{
                    $data_list['f2'] =  $r['date_from'];
                }
                $data_list['f3'] = $r['date_to'];
                $data_list['f4'] = $r['position_title'];
                $data_list['f5'] = $r['designation_title'];
                $data_list['f6'] = $r['salary'];
                $data_list['f7'] = $r['sg'];
                $data_list['f8'] = $r['step'];
                $data_list['f9'] = $r['type'];
                $data_list['f10'] = $r['emp_stat'];
                $data_list['f11'] = $r['office'];
                $data_list['f12'] = $r['lwop'];
                $data_list['f13'] = $r['separation'];
                $data_list['f14'] = $r['cause'];
                $data_list['f15'] = $r['remarks'];
                $data_list['f16'] = $r['docs'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    private function _newModal($request){
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $query = Users::where('id',$id)->first();
            $emp_stat = EmploymentStatus::get();
            $fund_source = FundSource::get();
            $fund_services = FundServices::get();
            $credit_type = HRCreditType::get();
            $user_role = UsersRole::where('id','>',1)->get();
            $work_type = _WorkType::get();
            $offices = Office::get();
            $data = array(
                'query' => $query,
                'emp_stat' => $emp_stat,
                'fund_source' => $fund_source,
                'fund_services' => $fund_services,
                'credit_type' => $credit_type,
                'user_role' => $user_role,
                'work_type' => $work_type,
                'offices' => $offices
            );
            return view('hrims/employee/work/newModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _editModal($request){
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $query = _Work::where('id',$id)->first();
            $emp_stat = EmploymentStatus::get();
            $fund_source = FundSource::get();
            $fund_services = FundServices::get();
            $credit_type = HRCreditType::get();
            $user_role = UsersRole::where('id','>',1)->get();
            $work_type = _WorkType::get();
            $offices = Office::get();
            $data = array(
                'query' => $query,
                'emp_stat' => $emp_stat,
                'fund_source' => $fund_source,
                'fund_services' => $fund_services,
                'credit_type' => $credit_type,
                'user_role' => $user_role,
                'work_type' => $work_type,
                'offices' => $offices
            );
            return view('hrims/employee/work/editModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _positionShortenGet($request){
        $id = $request->id;
        $result = 'error';
        $title = '';
        $shorten = '';
        $salary = '';
        $sg = '';
        $step = '';
        $emp_stat = '';
        $fund_source = '';
        $designation = '';
        $designation_name = '';
        $role = '';
        $gov_service = '';
        $query = HRPosition::with('designation')
            ->where('id',$id)->first();
        if($query!=NULL){
            $title = $query->name;
            $shorten = $query->shorten;
            $salary = $query->salary;
            $sg = $query->sg;
            $step = $query->step;
            $emp_stat = $query->emp_stat_id;
            $role = $query->role_id;
            $gov_service = $query->gov_service;
            if($query->fund_source_id==NULL){
                $fund_source = 'none';
            }else{
                $fund_source = $query->fund_source_id;
            }
            if($query->fund_services_id==NULL){
                $fund_services = 'none';
            }else{
                $fund_services = $query->fund_services_id;
            }
            if($query->designation_id==NULL){
                $designation = 'none';
                $designation_name = 'None';
            }else{
                $designation = $query->designation_id;
                $designation_name = $query->designation->name;
            }
            $result = 'success';
        }

        $data = array('result' => $result,
                      'title' => $title,
                      'shorten' => $shorten,
                      'salary' => $salary,
                      'sg' => $sg,
                      'step' => $step,
                      'emp_stat' => $emp_stat,
                      'fund_source' => $fund_source,
                      'fund_services' => $fund_services,
                      'role' => $role,
                      'designation' => $designation,
                      'designation_name' => $designation_name,
                      'gov_service' => $gov_service);
        return response()->json($data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to_option = $request->date_to_option;
            $date_to = $request->date_to;
            $position_option = $request->position_option;
            $position_id = $request->position_id;
            $position_title = $request->position_title;
            $position_shorten = $request->position_shorten;
            $salary = $request->salary;
            $sg = $request->sg;
            $step = $request->step;
            $gov_service = $request->gov_service;
            $emp_stat = $request->emp_stat;
            $fund_source = $request->fund_source;
            $fund_services = $request->fund_services;
            $designation = $request->designation;
            $credit_type = $request->credit_type;
            $role = $request->role;
            $type = $request->type;
            $office = $request->office;
            $office_id = $request->office_id;
            $oic = $request->oic;
            $separation = $request->separation;
            $cause = $request->cause;
            $lwop = $request->lwop;
            $remarks = $request->remarks;
            if($date_to_option=='present'){
                $date_to = 'present';
            }else{
                $date_to = date('Y-m-d',strtotime($date_to));
            }
            $lnu = NULL;
            if($position_option=='None'){
                $position_id = NULL;
            }else{
                $lnu = 1;
            }
            if($credit_type=='none'){
                $credit_type = NULL;
            }
            if($office_id==0){
                $office_id = NULL;
            }

            $getDesignation = HRDesignation::find($designation);
            if($getDesignation){
                $designation_title = $getDesignation->name;
                $designation_shorten = $getDesignation->shorten;
            }else{
                $designation = NULL;
                $credit_type = NULL;
                $designation_title = NULL;
                $designation_shorten = NULL;
            }
            $check_date = _Work::where('user_id',$id)
                ->where('date_from',$date_from)
                ->first();
            $check_present = NULL;
            $x = 0;
            if($date_to=='present'){
                $check_present = _Work::where('user_id',$id)
                    ->where('type_id',$type)
                    ->where('date_to','present')
                    ->first();
            }
            if($check_date!=NULL){
                $result = 'Date from already exists!';
                $x++;
            }
            if($check_present!=NULL){
                $result = 'Must only have 1 present per Option';
                $x++;
            }
            if($fund_source=='none'){
                $fund_source = NULL;
            }
            if($fund_services=='none'){
                $fund_services = NULL;
            }
            if($x==0){
                $insert = new _Work();
                $insert->user_id = $id;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->position_id = $position_id;
                $insert->role_id = $role;
                $insert->position_title = mb_strtoupper($position_title);
                $insert->position_shorten = mb_strtoupper($position_shorten);
                $insert->salary = $salary;
                $insert->sg = $sg;
                $insert->step = $step;
                $insert->gov_service = $gov_service;
                $insert->emp_stat_id = $emp_stat;
                $insert->fund_source_id = $fund_source;
                $insert->fund_services_id = $fund_services;
                $insert->designation_id = $designation;
                $insert->designation_title = $designation_title;
                $insert->designation_shorten = $designation_shorten;
                $insert->credit_type_id = $credit_type;
                $insert->office = $office;
                $insert->office_id = $office_id;
                $insert->oic = $oic;
                $insert->separation = $separation;
                $insert->cause = $cause;
                $insert->lwop = $lwop;
                $insert->type_id = $type;
                $insert->remarks = $remarks;
                $insert->lnu = $lnu;
                $insert->status = 1;
                $insert->updated_by = $updated_by;
                $insert->save();

                $employee = Users::find($id);

                _Work::where('user_id',$id)
                        ->where('date_from','<=',$date_from)
                        ->update(['status' => $employee->emp_status_id]);

                $this->updateCurrentPosition($position_option,$date_to,$position_id,$id,$office_id);

                $this->updateCurrentDesignation($getDesignation,$designation,$id);

                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _editSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to_option = $request->date_to_option;
            $date_to = $request->date_to;
            $position_option = $request->position_option;
            $position_id = $request->position_id;
            $position_title = $request->position_title;
            $position_shorten = $request->position_shorten;
            $salary = $request->salary;
            $sg = $request->sg;
            $step = $request->step;
            $gov_service = $request->gov_service;
            $emp_stat = $request->emp_stat;
            $fund_source = $request->fund_source;
            $fund_services = $request->fund_services;
            $designation = $request->designation;
            $credit_type = $request->credit_type;
            $role = $request->role;
            $type = $request->type;
            $office = $request->office;
            $office_id = $request->office_id;
            $oic = $request->oic;
            $separation = $request->separation;
            $cause = $request->cause;
            $lwop = $request->lwop;
            $remarks = $request->remarks;
            $work = _Work::find($id);
            $user_id = $work->user_id;
            if($date_to_option=='present'){
                $date_to = 'present';
            }else{
                $date_to = date('Y-m-d',strtotime($date_to));
            }
            $lnu = NULL;
            if($position_option=='None'){
                $position_id = NULL;
            }else{
                $lnu = 1;
            }
            if($credit_type=='none'){
                $credit_type = NULL;
            }
            if($office_id==0){
                $office_id = NULL;
            }
            $getDesignation = HRDesignation::find($designation);
            if($getDesignation){
                $designation_title = $getDesignation->name;
                $designation_shorten = $getDesignation->shorten;
            }else{
                $designation = NULL;
                $credit_type = NULL;
                $designation_title = NULL;
                $designation_shorten = NULL;
            }
            $check_date = _Work::where('id','!=',$id)
                ->where('user_id',$user_id)
                ->where('date_from',$date_from)
                ->first();
            $check_present = NULL;
            $x = 0;
            if($date_to=='present'){
                $check_present = _Work::whereNotIn('id',[$id])
                    ->where('user_id',$user_id)
                    ->where('type_id',$type)
                    ->where('date_to','present')
                    ->first();
            }
            if($check_date!=NULL){
                $result = 'Date from already exists!';
                $x++;
            }
            if($check_present!=NULL){
                $result = 'Must only have 1 present per Option';
                $x++;
            }
            if($fund_source=='none'){
                $fund_source = NULL;
            }
            if($fund_services=='none'){
                $fund_services = NULL;
            }

            if($x==0){
                $data = ['date_from' => $date_from,
                        'date_to' => $date_to,
                        'position_id' => $position_id,
                        'role_id' => $role,
                        'position_title' => mb_strtoupper($position_title),
                        'position_shorten' => mb_strtoupper($position_shorten),
                        'salary' => $salary,
                        'sg' => $sg,
                        'step' => $step,
                        'gov_service' => $gov_service,
                        'emp_stat_id' => $emp_stat,
                        'fund_source_id' => $fund_source,
                        'fund_services_id' => $fund_services,
                        'designation_id' => $designation,
                        'designation_title' => $designation_title,
                        'designation_shorten' => $designation_shorten,
                        'credit_type_id' => $credit_type,
                        'office' => $office,
                        'office_id' => $office_id,
                        'oic' => $oic,
                        'separation' => $separation,
                        'cause' => $cause,
                        'lwop' => $lwop,
                        'type_id' => $type,
                        'remarks' => $remarks,
                        'lnu' => $lnu,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')];
                $update = _Work::where('id', $id)
                            ->update($data);

                if($update){

                    $employee = Users::find($user_id);

                    _Work::where('user_id',$user_id)
                        ->where('date_from','<=',$date_from)
                        ->update(['status' => $employee->emp_status_id]);

                    $this->updateCurrentPosition($position_option,$date_to,$position_id,$user_id,$office_id);

                    $this->updateCurrentDesignation($getDesignation,$designation,$user_id);

                    $result = 'success';
                }
            }
            $query = _Work::where('id', $id)->first();
            if($query){
                $id = $query->user_id;
            }else{
                $id = NULL;
            }
        }
        $response = array('result' => $result,
                          'id' => $id
                        );
        return response()->json($response);
    }
    private function updateCurrentPosition($position_option,$date_to,$position_id,$user_id,$office_id)
    {
        if($position_option!='None' && $date_to=='present'){
            $update = HRPosition::find($position_id);
            $update->office_id = $office_id;
            $update->current_user_id = $user_id;
            $update->save();
        }
    }
    private function updateCurrentDesignation($getDesignation,$designation,$user_id)
    {
        if($getDesignation){
            $update = HRDesignation::find($designation);
            $update->current_user_id = $user_id;
            $update->save();
        }
    }
    private function entryUpdate(){

    }
}
?>
