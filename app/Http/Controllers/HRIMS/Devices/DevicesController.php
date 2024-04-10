<?php

namespace App\Http\Controllers\HRIMS\Devices;
use App\Http\Controllers\Controller;
use App\Models\Devices;
use App\Models\Users;
use App\Models\UsersDTRTrack;
use App\Services\NameServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Rats\Zkteco\Lib\ZKTeco;

class DevicesController extends Controller
{
    public function table(Request $request){
        $user_access_level = $request->session()->get('user_access_level'); 
        $data = array();
        $query = Devices::get()
            ->map(function($query) {
                $dateTime = '';
                if($query->status=='On'){
                    $dateTime = date('F d, Y h:i:sa',strtotime($query->dateTime));
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'ipaddress' => $query->ipaddress,
                    'port' => $query->port,
                    'remarks' => $query->remarks,
                    'status' => $query->status,
                    'dateTime' => $dateTime
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $dateTime = '';
                $clear = '';
                $status = '<button class="btn btn-success btn-success-scan"><span class="fa fa-check"></span> On</button>';
                if($r['dateTime']!=''){
                    $dateTime = '<button class="btn btn-primary btn-primary-scan btn-sm devicesDateTimeModal"
                                    data-id="'.$r['id'].'"
                                    data-s="'.$r['status'].'">
                                    <span class="fa fa-calendar"></span> '.$r['dateTime'].'
                                </button>';
                }
                
                if($r['status']=='' || $r['status']==NULL || $r['status']=='Off'){
                    $status = '<button class="btn btn-danger btn-danger-scan"><span class="fa fa-times"></span> Off</button>';
                }                
                if($user_access_level==1){
                    $clear = '<button class="btn btn-danger btn-danger-scan btn-sm logsClear"
                                data-id="'.$r['id'].'">
                                <span class="fa fa-times"></span> Clear
                            </button>';
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['ipaddress'];
                $data_list['f4'] = $r['port'];
                $data_list['f5'] = $r['remarks'];
                $data_list['f6'] = '<span class="devicesStatus" id="device_status_'.$r['id'].'">'.$status.'</span>';
                $data_list['f7'] = '<span class="devicesStatus" id="device_dateTime_'.$r['id'].'">'.$dateTime.'</span>';
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan btn-sm devices"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>
                                    <button class="btn btn-primary btn-primary-scan btn-sm logsAcquire"
                                        id="logs_acquire_'.$r['id'].'"
                                        data-id="'.$r['id'].'"
                                        data-s="'.$r['status'].'">
                                        <span class="fa fa-list"></span> Acquire
                                    </button>
                                    '.$clear;
                $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan btn-sm devicesEditModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span> Edit
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function newModal(Request $request){
        return view('hrims/devices/devicesNewModal');
    }
    public function editModal(Request $request){
        $id = $request->id;
        $device = Devices::find($id);
        if($device){
            $data = array(
                'device' => $device
            );     
            return view('hrims/devices/devicesEditModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    public function dateTimeModal(Request $request){
        $id = $request->id;
        $device = Devices::find($id);
        if($device){
            $data = array(
                'id' => $id,
                'device' => $device
            );     
            return view('hrims/devices/dateTimeModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    public function newModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $rules = [
                'name' => 'required',
                'ipaddress' => 'required',
                'port' => 'required|numeric'
            ];
        
            $customMessages = [
                'name.required' => 'Name is required.',
                'ipaddress.required' => 'Ipaddress is required.',
                'port.required' => 'Port is required.',
            ];
        
            $validator = Validator::make($request->all(), $rules, $customMessages);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400); // Return validation errors
            }
            try {
                $name = $request->name;
                $ipaddress = $request->ipaddress;
                $port = $request->port;
                $remarks = $request->remarks;
                $devicesCheck = Devices::where('name',$name)
                    ->orWhere('ipaddress',$ipaddress)
                    ->first();
                if($devicesCheck==NULL){
                    $insert = new Devices(); 
                    $insert->name = $name;
                    $insert->ipaddress = $ipaddress;
                    $insert->port = $port;
                    $insert->remarks = $remarks;
                    $insert->save();
                    $result = 'success';
                }else{
                    $result = 'Error! Device Name or Ipaddress exists!';
                }
            }catch(Exception $e){
                $result = $e;
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function editModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $rules = [
                'id' => 'required|numeric',
                'name' => 'required',
                'ipaddress' => 'required',
                'port' => 'required|numeric'
            ];
        
            $customMessages = [
                'id.required' => 'ID is required.',
                'name.required' => 'Name is required.',
                'ipaddress.required' => 'Ipaddress is required.',
                'port.required' => 'Port is required.',
            ];
        
            $validator = Validator::make($request->all(), $rules, $customMessages);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400); // Return validation errors
            }
            try {
                $id = $request->id;
                $name = $request->name;
                $ipaddress = $request->ipaddress;
                $port = $request->port;
                $remarks = $request->remarks;
                $devicesCheck = Devices::where('id','!=',$id)
                    ->where(function ($query) use ($name,$ipaddress) {
                        $query->where('name',$name)
                        ->orWhere('ipaddress',$ipaddress);
                    })
                    ->first();
                if($devicesCheck==NULL){
                    Devices::where('id', $id)
                                ->update(['name' => $name,
                                        'ipaddress' => $ipaddress,
                                        'port' => $port,
                                        'remarks' => $remarks]);
                    $result = 'success';
                }else{
                    $result = 'Error! Device Name or Ipaddress exists!';
                }
            }catch(Exception $e){
                $result = $e;
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function updateStatus(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        $devices = array();
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                $devices = Devices::where('device_status','Active')->get();
                if($devices->count()>0){
                    foreach($devices as $row){
                        $id = $row->id;
                        $ipaddress = $row->ipaddress;
                        $port = $row->port;
                        $zk = new ZKTeco($ipaddress,$port);
                        $status = 'Off';
                        $dateTime = NULL;
                        if ($zk->connect()){
                            $status = 'On';
                            $dateTime = date('Y-m-d H:i:s',strtotime($zk->getTime()));
                        }
                        Devices::where('id', $id)
                                ->update(['status' => $status,
                                        'dateTime' => $dateTime]);
                    }
                    Devices::where('id','>',0)
                            ->update(['queue' => 0]);
                    $result = 'success';
                    $devices = Devices::get()
                        ->map(function($query) {
                            $dateTime = '';
                            if($query->status=='On'){
                                $dateTime = date('F d, Y h:i:sa',strtotime($query->dateTime));
                            }
                            return [
                                'id' => $query->id,
                                'ipaddress' => $query->ipaddress,
                                'status' => $query->status,
                                'dateTime' => $dateTime
                            ];
                        })->toArray();
                }
            }catch(Exception $e) {
                    
            }
        }
        $response = array('result' => $result,
                          'devices' => $devices);
        return response()->json($response);
    }
    public function dateTimeModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        $devices = array();
        $dateTime = '';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                $id = $request->id;
                $date = $request->date;
                $time = $request->time;
                $device = Devices::find($id);
                if($device){
                    $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time));
                    $zk = new ZKTeco($device->ipaddress,$device->port);
                    if ($zk->connect()){
                        //$zk->setTime('2024-03-18 08:26:45');
                        $dateTime = date('Y-m-d H:i:s');
                        $zk->setTime($dateTime);
                        Devices::where('id', $id)
                                    ->update(['dateTime' => $dateTime]);
                        $dateTime = date('F d, Y h:ia', strtotime($date.' '.$time));
                        $result = 'success';
                    }
                }

                // $devices = Devices::where('device_status','Active')
                //     ->where('id','>=',7)
                //     ->where('id','<=',8)->get();
                // if($devices->count()>0){   
                //     $deviceIds = [];                 
                //     foreach($devices as $device){                        
                //         $zk = new ZKTeco($device->ipaddress,$device->port);
                //         if ($zk->connect()){
                //             $dateTime = date('Y-m-d H:i:s');
                //             //$zk->setTime('2023-08-24 10:26:15');
                //             $zk->setTime($dateTime);
                //             $deviceIds[] = $device->id;
                            
                //             $result = 'success';
                //         }
                //     }
                //     Devices::where('id', $deviceIds)
                //             ->update(['dateTime' => $dateTime]);
                //     $dateTime = date('F d, Y h:i:sa', strtotime($date.' '.$time));
                // }

            }catch(Exception $e) {
                    
            }
        }
        $response = array('result' => $result,
                          'id' => $id,
                          'dateTime' => $dateTime);
        return response()->json($response);
    }
}