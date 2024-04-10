<?php

namespace App\Imports;

use App\Models\DTRlogs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\UsersDTR;

class DTRImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function headingRow(): int
    {
        return 1;
    }
    //dtr
    public function model(array $row){
        $user_id = $row['id_no'];
        $state = $row['state']; //1 finger, 15 face
        $timestamp = date('Y-m-d H:i:s',strtotime(str_replace("'","",$row['timestamp'])));
        $type = $row['type']; //0 in, 1 out
        $ipaddress = $row['ipaddress'];
        // $delete = DTRlogs::where('id_no',$id_no)->where('dateTime',$timestamp)->delete();
        // $auto_increment = DB::update("ALTER TABLE dtr_logs AUTO_INCREMENT = 0;");
        $check_dtr = DTRlogs::where('id_no',$user_id)->where('dateTime',$timestamp)->first();
        $date = date('Y-m-d',strtotime($timestamp));
        if($check_dtr==NULL){

            $insert = new DTRlogs();
            $insert->device_id = 0;
            $insert->id_no = $user_id;
            $insert->state = $state;
            $insert->dateTime = $timestamp;
            $insert->type = $type;
            $insert->link = 0;
            $insert->skyhrImport = 0;
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
            //     // $insert = new UsersDTR();
            //     // $insert->id_no = $user_id;
            //     // $insert->date = $date;
            //     // $insert->$column = $timestamp;
            //     // $insert->$state_column = $state;
            //     // $insert->$ip_column = $ipaddress;
            //     // $insert->ipaddress = $ipaddress;
            //     // $insert->dateTime = $timestamp;
            //     // $insert->save();

            //     $values = array('id_no' => $user_id,
            //                     'date' => $date,
            //                     $column => $timestamp,
            //                     $state_column => $state,
            //                     $ip_column => $ipaddress,
            //                     'ipaddress' => $ipaddress,
            //                     'dateTime' => $timestamp,
            //                     'updated_at' => date('Y-m-d H:i:s'),
            //                     'created_at' => date('Y-m-d H:i:s'),
            //                 );
            //     UsersDTR::insert($values);
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
            //         ->where('date',$date)->first();
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
}
?>
