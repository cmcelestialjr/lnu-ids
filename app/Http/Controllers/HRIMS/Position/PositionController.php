<?php

namespace App\Http\Controllers\HRIMS\Position;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\EmploymentStatus;
use App\Models\FundServices;
use App\Models\FundSource;
use App\Models\HRDesignation;
use App\Models\HRPosition;
use App\Models\HRPositionSched;
use App\Models\HRPositionType;
use App\Models\Status;
use App\Models\Users;
use App\Models\UsersRole;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function positionTable(Request $request){
        return $this->table($request);
    }
    public function new(Request $request){
        return $this->_new($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function edit(Request $request){
        return $this->_edit($request);
    }
    public function editSubmit(Request $request){
        return $this->_editSubmit($request);
    }
    public function view(Request $request){
        return $this->_view($request);
    }
    public function viewTable(Request $request){
        return $this->_viewTable($request);
    }
    private function table($request){
        $data = array();
        $type = $request->type;
        $status = $request->status;
        $query = HRPosition::with('designation',
                                  'emp_stat',
                                  'fund_source',
                                  'role',
                                  'type_info')
            ->where('status_id',$status);
        if($type!='All'){
            $query = $query->where('type_id',$type);
        }
        $query =  $query->get()
            ->map(function($query) {
                $designation = '';
                if($query->designation_id!=NULL){
                    $designation = $query->designation->shorten;
                }
                $emp_stat = '';
                if($query->emp_stat){
                    $emp_stat = $query->emp_stat->name;
                }
                $fund_source = '';
                if($query->fund_source){
                    $fund_source = $query->fund_source->shorten;
                }
                $role = '';
                if($query->role){
                    $role = $query->role->name;
                }
                $type = '';
                if($query->type_id!=NULL){
                    $type = $query->type_info->name;
                }
                return [
                    'id' => $query->id,
                    'item_no' => $query->item_no,
                    'name' => $query->name,
                    'shorten' => $query->shorten,
                    'salary' => $query->salary,
                    'sg' => $query->sg,
                    'level' => $query->level,
                    'date_created' => date('M d, Y', strtotime($query->date_created)),
                    'emp_stat' => $emp_stat,
                    'fund_source' => $fund_source,
                    'role' => $role,
                    'type' => $type,
                    'designation' => $designation
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['item_no'].$status;
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['shorten'];
                $data_list['f5'] = $r['sg'];
                $data_list['f6'] = $r['level'];
                $data_list['f7'] = $r['type'];
                $data_list['f8'] = $r['emp_stat'];
                $data_list['f9'] = $r['fund_source'];
                $data_list['f10'] = $r['role'];
                $data_list['f11'] = $r['designation'];
                $data_list['f12'] = $r['date_created'];
                $data_list['f13'] = '<button class="btn btn-info btn-info-scan btn-sm positionEditModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                $data_list['f14'] = '<button class="btn btn-success btn-success-scan btn-sm positionViewModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _viewTable($request){
        $data = array();
        $name_services = new NameServices;
        $id = $request->id;
        $query =  _Work::select('user_id')
            ->where('position_id',$id)
            ->groupBY('user_id')->get()
            ->map(function($query) use ($name_services,$id) {
                $name = $name_services->lastname($query->user->lastname,$query->user->firstname,$query->user->middlename,$query->user->extname);
                $date_from = _Work::where('position_id',$id)
                    ->where('user_id',$query->user_id)
                    ->orderBy('date_from','ASC')->first();
                $date_to = _Work::where('position_id',$id)
                    ->where('user_id',$query->user_id)
                    ->orderBy('date_from','DESC')->first();
                return [
                    'date_from_sort' => $date_to->date_from,
                    'date_from' => date('M d, Y',strtotime($date_from->date_from)),
                    'date_to' => $date_to->date_to,
                    'id' => $query->user->id_no,
                    'name' => $name,
                    'salary' => $date_to->salary,
                    'sg' => $date_to->sg,
                    'step' => $date_to->step,
                    'separation' => $date_to->separation,
                    'date_separation' => $date_to->date_separation,
                    'remarks' => $date_to->remarks
                ];
            })->toArray();
        $query = collect($query)->sortByDesc('date_from_sort');
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['date_from'];
                $data_list['f3'] = $r['date_to'];
                $data_list['f4'] = $r['id'];
                $data_list['f5'] = $r['name'];
                $data_list['f6'] = $r['salary'];
                $data_list['f7'] = $r['sg'];
                $data_list['f8'] = $r['step'];
                $data_list['f9'] = $r['separation'];
                $data_list['f10'] = $r['date_separation'];
                $data_list['f11'] = $r['remarks'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _new($request){
        $status = Status::whereHas('status_list', function ($query) {
                $query->where('table','position');
            })->get();
        $emp_stat = EmploymentStatus::get();
        $fund_source = FundSource::get();
        $fund_services = FundServices::get();
        $sched = HRPositionSched::get();
        $role = UsersRole::where('id','>',1)->get();

        $data = array(
            'status' => $status,
            'emp_stat' => $emp_stat,
            'fund_source' => $fund_source,
            'fund_services' => $fund_services,
            'sched' => $sched,
            'role' => $role
        );
        return view('hrims/position/newModal',$data);
    }
    private function _edit($request){
        $query = HRPosition::where('id',$request->id)->first();
        $status = Status::whereHas('status_list', function ($query) {
            $query->where('table','position');
        })->get();
        $emp_stat = EmploymentStatus::get();
        $fund_source = FundSource::get();
        $fund_services = FundServices::get();
        $sched = HRPositionSched::get();
        $role = UsersRole::where('id','>',1)->get();

        $data = array(
            'status' => $status,
            'emp_stat' => $emp_stat,
            'fund_source' => $fund_source,
            'fund_services' => $fund_services,
            'role' => $role,
            'sched' => $sched,
            'query' => $query
        );
        return view('hrims/position/editModal',$data);
    }
    private function _view($request){
        $query = HRPosition::where('id',$request->id)->first();

        $data = array(
            'query' => $query
        );
        return view('hrims/position/viewModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $item_no = $request->item_no;
            $name = mb_strtoupper($request->name);
            $shorten = mb_strtoupper($request->shorten);
            $salary = $request->salary;
            $sg = $request->sg;
            $level = $request->level;
            $date_created = $request->date_created;
            $remarks = $request->remarks;
            $designation = $request->designation;
            $emp_stat = $request->emp_stat;
            $fund_source = $request->fund_source;
            $fund_services = $request->fund_services;
            $role = $request->role;
            $status = $request->status;
            $sched =$request->sched;
            $gov_service = $request->gov_service;
            $check = HRPosition::where('item_no',$item_no)->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                if($designation=='none'){
                    $designation = NULL;
                }
                if($remarks==''){
                    $remarks = NULL;
                }
                if($fund_source==''){
                    $fund_source = NULL;
                }
                if($fund_services==''){
                    $fund_services = NULL;
                }
                $insert = new HRPosition();
                $insert->item_no = $item_no;
                $insert->name = $name;
                $insert->shorten = $shorten;
                $insert->salary = $salary;
                $insert->sg = $sg;
                $insert->step = 1;
                $insert->level = $level;
                $insert->date_created = date('Y-m-d',strtotime($date_created));
                $insert->remarks = $remarks;
                $insert->designation_id = $designation;
                $insert->emp_stat_id = $emp_stat;
                $insert->fund_source_id = $fund_source;
                $insert->fund_services_id = $fund_services;
                $insert->role_id = $role;
                $insert->type_id = 2;
                $insert->status_id = $status;
                $insert->sched_id = $sched;
                $insert->gov_service = $gov_service;
                $insert->updated_by = $updated_by;
                $insert->save();
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
            $id = $request->id;
            $item_no = $request->item_no;
            $name = mb_strtoupper($request->name);
            $shorten = mb_strtoupper($request->shorten);
            $salary = $request->salary;
            $sg = $request->sg;
            $level = $request->level;
            $date_created = $request->date_created;
            $remarks = $request->remarks;
            $designation = $request->designation;
            $emp_stat = $request->emp_stat;
            $fund_source = $request->fund_source;
            $fund_services = $request->fund_services;
            $role = $request->role;
            $status = $request->status;
            $sched = $request->sched;
            $gov_service = $request->gov_service;
            $check = HRPosition::where('item_no',$item_no)->where('id','!=',$id)->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                if($designation=='none'){
                    $designation = NULL;
                }
                if($remarks==''){
                    $remarks = NULL;
                }
                if($fund_source==''){
                    $fund_source = NULL;
                }
                if($fund_services==''){
                    $fund_services = NULL;
                }
                $get = _Work::where('position_id',$id)->where('date_to','present')->first();
                $type_id = 2;
                if($get!=NULL){
                    $type_id = 1;
                }
                $data = ['item_no' => $item_no,
                        'name' => $name,
                        'shorten' => $shorten,
                        'salary' => $salary,
                        'sg' => $sg,
                        'step' => 1,
                        'level' => $level,
                        'date_created' => date('Y-m-d',strtotime($date_created)),
                        'remarks' => $remarks,
                        'designation_id' => $designation,
                        'emp_stat_id' => $emp_stat,
                        'fund_source_id' => $fund_source,
                        'fund_services_id' => $fund_services,
                        'role_id' => $role,
                        'type_id' => $type_id,
                        'status_id' => $status,
                        'sched_id' => $sched,
                        'gov_service' => $gov_service,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')];
                $update = HRPosition::where('id', $id)
                            ->update($data);
                if($update){
                    $result = 'success';
                }
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}
?>
