<?php

namespace App\Http\Controllers\HRIMS\Devices;
use App\Http\Controllers\Controller;
use App\Models\Devices;
use App\Models\DTRlogs;
use App\Models\UsersDTR;
use Exception;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class LogsController extends Controller
{   
    public function acquire(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $getDevice = Devices::find($id);
            if($getDevice){
                $ipaddress = $getDevice->ipaddress;
                $port = $getDevice->port;
                $zk = new ZKTeco($ipaddress,$port);
                if ($zk->connect()){
                    //$zk->clearAttendance();
                    $attendace = $zk->getAttendance();
                    foreach($attendace as $row){
                        $user_id = $row['id'];
                        $state = $row['state']; //1 finger, 15 face, 16 face
                        $timestamp = date('Y-m-d H:i:s',strtotime($row['timestamp']));
                        $type = $row['type']; //0 in, 1 out
                        
                        $check = DTRlogs::where('id_no',$user_id)
                            ->where('dateTime',$timestamp)->first();
                        if($check==NULL){
                            $insert = new DTRlogs();
                            $insert->id_no = $user_id;
                            $insert->state = $state;
                            $insert->dateTime = $timestamp;
                            $insert->type = $type;
                            $insert->link = 0;
                            $insert->ipaddress = $ipaddress;
                            $insert->save();
    
                            // $time = date('H:i',strtotime($timestamp));
                            // $date = date('Y-m-d',strtotime($timestamp));
                            // $check = UsersDTR::where('id_no',$user_id)
                            //     ->where('date',$date)->first();
                            // if($time<'12:00'){
                            //     if($type==0 || $type==3){
                            //         $column = 'time_in_am';
                            //         $state_column = 'state_in_am';
                            //         $ip_column = 'ipaddress_in_am';
                            //     }else{
                            //         $column = 'time_out_am';
                            //         $state_column = 'state_out_am';
                            //         $ip_column = 'ipaddress_out_am';
                            //     }
                            // }elseif($time>='12:00' && $time<='13:00'){
                            //     if($type==0 || $type==3){
                            //         $column = 'time_in_pm';
                            //         $state_column = 'state_in_pm';
                            //         $ip_column = 'ipaddress_in_pm';
                            //     }else{
                            //         $column = 'time_out_am';
                            //         $state_column = 'state_out_am';
                            //         $ip_column = 'ipaddress_out_am';
                            //     }
                            // }else{
                            //     if($type==0 || $type==3){
                            //         $column = 'time_in_pm';
                            //         $state_column = 'state_in_pm';
                            //         $ip_column = 'ipaddress_in_pm';
                            //     }else{
                            //         $column = 'time_out_pm';
                            //         $state_column = 'state_out_pm';
                            //         $ip_column = 'ipaddress_out_pm';
                            //     }
                            // }
                            // if($check==NULL){
                            //     $insert = new UsersDTR();
                            //     $insert->id_no = $user_id;
                            //     $insert->date = $date;
                            //     $insert->$column = $timestamp;
                            //     $insert->$state_column = $state;
                            //     $insert->$ip_column = $ipaddress;
                            //     $insert->ipaddress = $ipaddress;
                            //     $insert->dateTime = $timestamp;
                            //     $insert->save();
                            // }else{
                            //     if($time>='12:00' && $check->time_in_pm>$timestamp && $check->time_out_am==NULL && $check->time_in_pm!=NULL && $type==1){
                            //         $column = 'time_out_pm';
                            //         $state_column = 'state_out_pm';
                            //     }elseif($time<'12:00' && $check->time_in_am>=$timestamp && $check->time_out_am==NULL && $check->time_in_am!=NULL && $type==1){
                            //         $column = 'time_in_am';
                            //         $state_column = 'state_in_am';
                            //     }
                            //     if($time!=date('H:i',strtotime($check->$column)) && $check->$column==NULL){
                            //         UsersDTR::where('id_no',$user_id)
                            //                 ->where('date',$date)
                            //                 ->update([$column => $timestamp,
                            //                         $state_column => $state,
                            //                         $ip_column => $ipaddress,
                            //                         'ipaddress' => $ipaddress,
                            //                         'dateTime' => $timestamp,
                            //                         'updated_at' => date('Y-m-d H:i:s')]);
                            //     }
                            //     UsersDTR::where('id_no',$user_id)
                            //                 ->where('date',$date)
                            //                 ->update(['ipaddress' => $ipaddress,
                            //                         'dateTime' => $timestamp,
                            //                         'time_type' => NULL,
                            //                         'updated_at' => date('Y-m-d H:i:s')]);
                            // }
                            // $check = UsersDTR::where('id_no',$user_id)
                            //     ->where('date',$date)->first();
                            // if($check!=NULL){
                            //     if($check->time_out_am<=$check->time_in_am && $check->time_in_am!=NULL && $check->time_out_am!=NULL){
                            //         UsersDTR::where('id_no',$user_id)
                            //                 ->where('date',$date)
                            //                 ->update(['time_out_am' => NULL,
                            //                         'state_out_am' => NULL,
                            //                         'ipaddress_out_am' => NULL]);
                            //     }
                            //     if($check->time_in_pm<=$check->time_out_am && $check->time_in_pm!=NULL && $check->time_out_am!=NULL){
                            //         UsersDTR::where('id_no',$user_id)
                            //                 ->where('date',$date)
                            //                 ->update(['time_in_pm' => NULL,
                            //                         'state_in_pm' => NULL,
                            //                         'ipaddress_in_pm' => NULL]);
                            //     }
                            //     if($check->time_out_pm<=$check->time_in_pm && $check->time_out_pm!=NULL && $check->time_in_pm!=NULL){
                            //         UsersDTR::where('id_no',$user_id)
                            //                 ->where('date',$date)
                            //                 ->update(['time_out_pm' => NULL,
                            //                         'state_out_pm' => NULL,
                            //                         'ipaddress_out_pm' => NULL]);
                            //     }
                            // }
                        }
                    }
                    $result = 'success';
                }
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function clear(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1){
            try{
                $id = $request->id;
                $device = Devices::find($id);
                if($device){
                    $zk = new ZKTeco($device->ipaddress,$device->port);
                    if ($zk->connect()){
                        $zk->clearAttendance();
                        $result = 'success';
                    }
                }
            }catch(Exception $e) {
                $result = $e;
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}