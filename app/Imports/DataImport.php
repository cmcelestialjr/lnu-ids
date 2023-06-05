<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\_Work;
use App\Models\DTRlogs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;
use App\Models\PSGCBrgys;
use App\Models\PSGCCityMuns;
use App\Models\PSGCProvinces;
use App\Models\PSGCRegions;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersRoleList;
use App\Models\UsersSystems;
use App\Models\UsersSystemsNav;
use App\Services\NameServices;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataImport implements ToModel, WithHeadingRow
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
    //programs
    // public function model(array $row)
    // {
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducPrograms();        
    //     $insert->department_id = $row["department_id"];
    //     $insert->program_level_id = $row["program_level_id"];
    //     $insert->name = $row["name"];
    //     $insert->shorten = $row["shorten"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }
    
    // public function model(array $row)
    // {
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducPrograms();        
    //     $insert->department_id = $row["department_id"];
    //     $insert->program_level_id = $row["program_level_id"];
    //     $insert->name = $row["name"];
    //     $insert->shorten = $row["shorten"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }

    //psgc    
    // public function model(array $row)
    // {
    //     // $user = Auth::User();
    //     // $user_id = $user->id;
    //     // $insert = new PSGCBrgys();        
    //     // $insert->name = $row["name"];
    //     // $insert->uacs = $row["uacs"];
    //     // $insert->city_mun_uacs = $row["city_mun_uacs"];
    //     // $insert->updated_by = $user_id;
    //     // $insert->save();

    //     PSGCCityMuns::where('uacs', $row['uacs'])
    //                             ->update(['type' => $row["type"]
    //                                     ]);
    // }
    
    //dtr
    public function model(array $row){
        $id_no = $row['id_no'];
        $state = $row['state']; //1 finger, 15 face
        $timestamp = str_replace("'","",$row['timestamp']);
        $type = $row['type']; //0 in, 1 out
        $ipaddress = $row['ipaddress'];
        // $delete = DTRlogs::where('id_no',$id_no)->where('dateTime',$timestamp)->delete();
        // $auto_increment = DB::update("ALTER TABLE dtr_logs AUTO_INCREMENT = 0;");
        $check_dtr = DTRlogs::where('id_no',$id_no)->where('dateTime',$timestamp)->first();
        $date = date('Y-m-d',strtotime($timestamp));
        if($check_dtr==NULL){
                    $insert = new DTRlogs();
                    $insert->id_no = $id_no;
                    $insert->state = $state;
                    $insert->dateTime = $timestamp;
                    $insert->type = $type;
                    $insert->ipaddress = $ipaddress;
                    $insert->save();

                    $time = date('H:i',strtotime($timestamp));
                    // UsersDTR::where('id_no',$id_no)
                    //             ->where('date',$date)
                    //                 ->where('time_out_am',$timestamp)
                    //                 ->update(['time_out_am' => NULL]);
                    // UsersDTR::where('id_no',$id_no)
                    //             ->where('date',$date)
                    //                 ->where('time_in_pm',$timestamp)
                    //                 ->update(['time_in_pm' => NULL]);

                    $check = UsersDTR::where('id_no',$id_no)
                        ->where('date',$date)->first();
                    if($time<'12:00'){
                        if($type==0 || $type==3){
                            $column = 'time_in_am';
                            $state_column = 'state_in_am';
                            $ip_column = 'ipaddress_in_am';
                        }else{
                            $column = 'time_out_am';
                            $state_column = 'state_out_am';
                            $ip_column = 'ipaddress_out_am';
                        }
                    }elseif($time>='12:00' && $time<='13:00'){
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                        }else{
                            $column = 'time_out_am';
                            $state_column = 'state_out_am';
                            $ip_column = 'ipaddress_out_am';
                        }
                    }else{
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                        }else{
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                            $ip_column = 'ipaddress_out_pm';
                        }
                    }
                    if($check==NULL){
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no;
                        $insert->date = $date;
                        $insert->$column = $timestamp;
                        $insert->$state_column = $state;
                        $insert->$ip_column = $ipaddress;
                        $insert->ipaddress = $ipaddress;
                        $insert->dateTime = $timestamp;
                        $insert->save();
                    }else{
                        if($time>='12:00' && $check->time_in_pm>$timestamp && $check->time_out_am==NULL && $check->time_in_pm!=NULL && $type==1){
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                        }elseif($time<'12:00' && $check->time_in_am>=$timestamp && $check->time_out_am==NULL && $check->time_in_am!=NULL && $type==1){
                            $column = 'time_in_am';
                            $state_column = 'state_in_am';
                        }
                        if($time!=date('H:i',strtotime($check->$column)) && $check->$column==NULL){
                            UsersDTR::where('id_no',$id_no)
                                    ->where('date',$date)
                                    ->update([$column => $timestamp,
                                            $state_column => $state,
                                            $ip_column => $ipaddress,
                                            'ipaddress' => $ipaddress,
                                            'dateTime' => $timestamp,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                        }
                        UsersDTR::where('id_no',$id_no)
                                    ->where('date',$date)
                                    ->update(['ipaddress' => $ipaddress,
                                            'dateTime' => $timestamp,
                                            'time_type' => NULL,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                    }   
                    $check = UsersDTR::where('id_no',$id_no)
                    ->where('date',$date)->first();
            if($check!=NULL){
                if($check->time_out_am<=$check->time_in_am && $check->time_in_am!=NULL && $check->time_out_am!=NULL){
                    UsersDTR::where('id_no',$id_no)
                            ->where('date',$date)
                            ->update(['time_out_am' => NULL,
                                    'state_out_am' => NULL,
                                    'ipaddress_out_am' => NULL]);
                }
                if($check->time_in_pm<=$check->time_out_am && $check->time_in_pm!=NULL && $check->time_out_am!=NULL){
                    UsersDTR::where('id_no',$id_no)
                            ->where('date',$date)
                            ->update(['time_in_pm' => NULL,
                                    'state_in_pm' => NULL,
                                    'ipaddress_in_pm' => NULL]);
                }
                if($check->time_out_pm<=$check->time_in_pm && $check->time_out_pm!=NULL && $check->time_in_pm!=NULL){
                    UsersDTR::where('id_no',$id_no)
                            ->where('date',$date)
                            ->update(['time_out_pm' => NULL,
                                    'state_out_pm' => NULL,
                                    'ipaddress_out_pm' => NULL]);
                }
            }                 
        }
        
    }

    //employeeinfo
    // public function model(array $row)
    // {
    //     $name_services = new NameServices;
    //     $password = Crypt::encryptString('1234'.Hash::make('1234').'1234');
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new Users();        
    //     $insert->username = $row["id_no"];
    //     $insert->password = $password;
    //     $insert->lastname = mb_strtoupper($row["lastname"]);
    //     $insert->firstname = mb_strtoupper($row["firstname"]);
    //     $insert->middlename = mb_strtoupper($row["middlename"]);
    //     $insert->extname = mb_strtoupper($row["extname"]);
    //     $insert->id_no = $row["id_no"];
    //     $insert->level_id = 6;
    //     $insert->status_id = 1;
    //     $insert->user_id = $user_id;
    //     $insert->save();
    //     $get_id = $insert->id;

    //     $insert = new _PersonalInfo();        
    //     $insert->user_id = $get_id;
    //     $insert->email = $row["email"];
    //     $insert->email_official = $row["email"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();

    //     $insert = new UsersRoleList();
    //     $insert->user_id = $get_id;
    //     $insert->role_id = $row['faculty'];
    //     $insert->emp_stat = $row["emp_stat"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();

    //     if($row['position_title']!=''){
    //         $insert = new _Work();        
    //         $insert->user_id = $get_id;
    //         $insert->role_id = $row['faculty'];
    //         $insert->emp_stat_id = $row["emp_stat"];
    //         $insert->date_from = date('Y-m-d',strtotime(str_replace("'","",$row["date_from"])));
    //         $insert->date_to = 'present';
    //         $insert->position_title = $row["position_title"];
    //         $insert->sg = $row["sg"];
    //         $insert->office = 'LNU';
    //         $insert->updated_by = $user_id;
    //         $insert->save();
    //     }

    //     $insert = new UsersSystems();        
    //     $insert->user_id = $get_id;        
    //     $insert->system_id = 6;
    //     $insert->role_id = $row['faculty'];
    //     $insert->level_id = 6;
    //     $insert->updated_by = $user_id;
    //     $insert->save();

    //     $insert = new UsersSystemsNav();        
    //     $insert->user_id = $get_id;
    //     $insert->system_nav_id = 27;
    //     $insert->role_id = $row['faculty'];
    //     $insert->level_id = 6;
    //     $insert->updated_by = $user_id;
    //     $insert->save();

    // }
}
?>
