<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\_Work;
use App\Models\AccAccountTitle;
use App\Models\DTRlogs;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducDepartmentUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;
use App\Models\LMSBooksInfo;
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
    // public function model(array $row){
    //     $id_no = $row['id_no'];
    //     $state = $row['state']; //1 finger, 15 face
    //     $timestamp = str_replace("'","",$row['timestamp']);
    //     $type = $row['type']; //0 in, 1 out
    //     $ipaddress = $row['ipaddress'];
    //     // $delete = DTRlogs::where('id_no',$id_no)->where('dateTime',$timestamp)->delete();
    //     // $auto_increment = DB::update("ALTER TABLE dtr_logs AUTO_INCREMENT = 0;");
    //     $check_dtr = DTRlogs::where('id_no',$id_no)->where('dateTime',$timestamp)->first();
    //     $date = date('Y-m-d',strtotime($timestamp));
    //     if($check_dtr==NULL){
    //                 $insert = new DTRlogs();
    //                 $insert->id_no = $id_no;
    //                 $insert->state = $state;
    //                 $insert->dateTime = $timestamp;
    //                 $insert->type = $type;
    //                 $insert->ipaddress = $ipaddress;
    //                 $insert->save();

    //                 $time = date('H:i',strtotime($timestamp));
    //                 // UsersDTR::where('id_no',$id_no)
    //                 //             ->where('date',$date)
    //                 //                 ->where('time_out_am',$timestamp)
    //                 //                 ->update(['time_out_am' => NULL]);
    //                 // UsersDTR::where('id_no',$id_no)
    //                 //             ->where('date',$date)
    //                 //                 ->where('time_in_pm',$timestamp)
    //                 //                 ->update(['time_in_pm' => NULL]);

    //                 $check = UsersDTR::where('id_no',$id_no)
    //                     ->where('date',$date)->first();
    //                 if($time<'12:00'){
    //                     if($type==0 || $type==3){
    //                         $column = 'time_in_am';
    //                         $state_column = 'state_in_am';
    //                         $ip_column = 'ipaddress_in_am';
    //                     }else{
    //                         $column = 'time_out_am';
    //                         $state_column = 'state_out_am';
    //                         $ip_column = 'ipaddress_out_am';
    //                     }
    //                 }elseif($time>='12:00' && $time<='13:00'){
    //                     if($type==0 || $type==3){
    //                         $column = 'time_in_pm';
    //                         $state_column = 'state_in_pm';
    //                         $ip_column = 'ipaddress_in_pm';
    //                     }else{
    //                         $column = 'time_out_am';
    //                         $state_column = 'state_out_am';
    //                         $ip_column = 'ipaddress_out_am';
    //                     }
    //                 }else{
    //                     if($type==0 || $type==3){
    //                         $column = 'time_in_pm';
    //                         $state_column = 'state_in_pm';
    //                         $ip_column = 'ipaddress_in_pm';
    //                     }else{
    //                         $column = 'time_out_pm';
    //                         $state_column = 'state_out_pm';
    //                         $ip_column = 'ipaddress_out_pm';
    //                     }
    //                 }
    //                 if($check==NULL){
    //                     $insert = new UsersDTR();
    //                     $insert->id_no = $id_no;
    //                     $insert->date = $date;
    //                     $insert->$column = $timestamp;
    //                     $insert->$state_column = $state;
    //                     $insert->$ip_column = $ipaddress;
    //                     $insert->ipaddress = $ipaddress;
    //                     $insert->dateTime = $timestamp;
    //                     $insert->save();
    //                 }else{
    //                     if($time>='12:00' && $check->time_in_pm>$timestamp && $check->time_out_am==NULL && $check->time_in_pm!=NULL && $type==1){
    //                         $column = 'time_out_pm';
    //                         $state_column = 'state_out_pm';
    //                     }elseif($time<'12:00' && $check->time_in_am>=$timestamp && $check->time_out_am==NULL && $check->time_in_am!=NULL && $type==1){
    //                         $column = 'time_in_am';
    //                         $state_column = 'state_in_am';
    //                     }
    //                     if($time!=date('H:i',strtotime($check->$column)) && $check->$column==NULL){
    //                         UsersDTR::where('id_no',$id_no)
    //                                 ->where('date',$date)
    //                                 ->update([$column => $timestamp,
    //                                         $state_column => $state,
    //                                         $ip_column => $ipaddress,
    //                                         'ipaddress' => $ipaddress,
    //                                         'dateTime' => $timestamp,
    //                                         'updated_at' => date('Y-m-d H:i:s')]);
    //                     }
    //                     UsersDTR::where('id_no',$id_no)
    //                                 ->where('date',$date)
    //                                 ->update(['ipaddress' => $ipaddress,
    //                                         'dateTime' => $timestamp,
    //                                         'time_type' => NULL,
    //                                         'updated_at' => date('Y-m-d H:i:s')]);
    //                 }   
    //                 $check = UsersDTR::where('id_no',$id_no)
    //                 ->where('date',$date)->first();
    //         if($check!=NULL){
    //             if($check->time_out_am<=$check->time_in_am && $check->time_in_am!=NULL && $check->time_out_am!=NULL){
    //                 UsersDTR::where('id_no',$id_no)
    //                         ->where('date',$date)
    //                         ->update(['time_out_am' => NULL,
    //                                 'state_out_am' => NULL,
    //                                 'ipaddress_out_am' => NULL]);
    //             }
    //             if($check->time_in_pm<=$check->time_out_am && $check->time_in_pm!=NULL && $check->time_out_am!=NULL){
    //                 UsersDTR::where('id_no',$id_no)
    //                         ->where('date',$date)
    //                         ->update(['time_in_pm' => NULL,
    //                                 'state_in_pm' => NULL,
    //                                 'ipaddress_in_pm' => NULL]);
    //             }
    //             if($check->time_out_pm<=$check->time_in_pm && $check->time_out_pm!=NULL && $check->time_in_pm!=NULL){
    //                 UsersDTR::where('id_no',$id_no)
    //                         ->where('date',$date)
    //                         ->update(['time_out_pm' => NULL,
    //                                 'state_out_pm' => NULL,
    //                                 'ipaddress_out_pm' => NULL]);
    //             }
    //         }                 
    //     }
        
    // }

    //educ_course
    public function model(array $row)
    {
        $user = Auth::User();
        $user_id = $user->id;
        $insert = new EducCourses();
        $insert->curriculum_id = $row["curriculum_id"];
        $insert->grade_level_id = $row["grade_level_id"];
        $insert->grade_period_id = $row["grade_period_id"];
        $insert->name = $row["name"];
        $insert->shorten = $row["shorten"];
        $insert->code = $row["code"];
        $insert->units = $row["units"];
        $insert->pay_units = $row["units"];
        $insert->description = $row["name"];
        $insert->course_type_id = $row["course_type_id"];
        $insert->updated_by = $user_id;
        $insert->save();
    }

    //educ_curriculum
    // public function model(array $row)
    // {
    //     $user = Auth::User();
    //     $user_id = $user->id;

    //     $curriculum_id = $row["curriculum_id"];

    //     if($row['year_from']>0){
    //         $year_from = $row['year_from'];
    //     }else{
    //         $year_from = NULL;
    //     }

    //     $check = EducCurriculum::find($curriculum_id);
    //     if($check){
    //         EducCurriculum::where('id', $curriculum_id)
    //             ->update(['program_id' => $row["program_id"],
    //                       'name' => $row["name"],
    //                       'year_from' => $year_from,
    //                       'code' => $row["code"],
    //                       'status_id' => $row["status_id"],
    //                       'remarks' => $row["remarks"],
    //                       'updated_by' => $user_id,
    //                       'updated_at' => date('Y-m-d H:i:s')]);
    //     }else{
    //         $insert = new EducCurriculum();
    //         $insert->id = $curriculum_id;
    //         $insert->program_id = $row["program_id"];
    //         $insert->name = $row["name"];
    //         $insert->year_from = $year_from;
    //         $insert->code = $row["code"];
    //         $insert->status_id = $row["status_id"];
    //         $insert->remarks = $row["remarks"];
    //         $insert->updated_by = $user_id;
    //         $insert->save();
    //     }
    // }

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


    //educ_table
    // public function model(array $row)
    // {
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducDepartmentUnit();
    //     $insert->id = $row["id"];
    //     $insert->department_id = $row["department_id"];
    //     $insert->name = $row["name"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }

    //educ_programs
    // public function model(array $row)
    // {
    //     if($row["department_unit_id"]==''){
    //         $department_unit_id = NULL;
    //     }else{
    //         $department_unit_id = $row["department_unit_id"];
    //     }
    //     if($row["program_level_id"]==''){
    //         $program_level_id = NULL;
    //     }else{
    //         $program_level_id = $row["program_level_id"];
    //     }
    //     if($row["name"]==''){
    //         $name = NULL;
    //     }else{
    //         $name = $row["name"];
    //     }
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducPrograms();        
    //     $insert->id = $row["id"];
    //     $insert->department_id = $row["department_id"];
    //     $insert->department_unit_id = $department_unit_id;
    //     $insert->program_level_id = $program_level_id;
    //     $insert->name = $name;
    //     $insert->shorten = $row["shorten"];
    //     $insert->status_id = $row["status_id"];
    //     $insert->accredited = $row["accredited"];
    //     $insert->remarks = $row["remarks"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }

    //educ_curriculum
    // public function model(array $row)
    // {   
    //     $program_id = $row["program_id"];
    //     $program = EducPrograms::find($program_id);
    //     $query = EducCurriculum::where('program_id',$program_id)
    //         ->orderBy('year_from','DESC')
    //         ->orderBy('id','DESC')->first();
    //     if($query!=NULL){
    //         $code = $this->alphabet($query->code);
    //     }else{
    //         $code = 'A';
    //     }
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducCurriculum();        
    //     $insert->id = $row["id"];
    //     $insert->program_id = $program_id;
    //     $insert->name = $row["name"];
    //     $insert->year_from = $row["year_from"];
    //     $insert->code = $code;
    //     $insert->status_id = $program->status_id;
    //     $insert->remarks = $row["remarks"];        
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }
    // private function alphabet($letter){
    //     $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    //     $add = 0;
    //     if(strlen($letter)>1){
    //         $add = $letter[1];
    //         $letter = $letter[0];            
    //     }        
    //     if($letter!=NULL){
    //         $key = array_search($letter, $alphabet);
    //     }else{
    //         $key = 0;
    //     }
    //     if($letter=='Z'){
    //         $add = $add+1;
    //         $key = -1;
    //     }
    //     if($add==0){
    //         $add = '';
    //     }
    //     $letter = $alphabet[$key+1].$add;
    //     return $letter;
    // }

    // educ_courses
    // public function model(array $row)
    // {   
    //     $curriculum_id = $row["curriculum_id"];
    //     $curriculum = EducCurriculum::find($curriculum_id);
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducCourses();
    //     $insert->curriculum_id = $curriculum_id;
    //     $insert->grade_level_id = $row["grade_level_id"];
    //     $insert->grade_period_id = $row["grade_period_id"];
    //     $insert->name = $row["name"];
    //     $insert->shorten = $row["shorten"];
    //     $insert->code = $row["shorten"];
    //     $insert->units = $row["units"];
    //     $insert->lab = $row["lab"];
    //     $insert->pay_units = $row["pay_units"];
    //     $insert->status_id = $curriculum->status_id;
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }

    // public function model(array $row)
    // {  
    //     $publisher = ($row["publisher"]=='') ? NULL : $row["publisher"];
    //     $subject = ($row["subject"]=='') ? NULL : $row["subject"];
    //     $year = ($row["year"]=='') ? NULL : $row["year"];

    //     $insert = new LMSBooksInfo();
    //     $insert->title = $row["title"];
    //     $insert->publisher = $publisher;
    //     $insert->subject = $subject;
    //     $insert->year = $year;
    //     $insert->save();
    // }

    // public function model(array $row)
    // {  
    //     $name = ($row["name"]=='') ? NULL : $row["name"];
    //     $code = ($row["code"]=='') ? NULL : $row["code"];

    //     $insert = new AccAccountTitle();
    //     $insert->name = $name;
    //     $insert->code = $code;
    //     $insert->save();
    // }
}
?>
