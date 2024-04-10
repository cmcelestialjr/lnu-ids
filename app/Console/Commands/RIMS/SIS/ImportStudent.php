<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\_PersonalInfo;
use App\Models\CivilStatuses;
use App\Models\Users;
use App\Models\UsersRoleList;
use App\Models\UsersSystems;
use App\Models\UsersSystemsNav;
use App\Services\EncryptServices;
use App\Services\TokenServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Steps in importing Student from sys
        //1. ImportStudent
        //2. ImportStudentProgram
        //3. ImportStudentCurriculum
        //4. ImportStudentInfo

        $connectionName = 'sis_student';
        DB::connection($connectionName)->getPdo();
        
        $users = Users::where('stud_id','!=', NULL)->pluck('stud_id');
        $students = DB::connection($connectionName)->table('info')
                        // ->whereNotIn('stud_id',$users)
                        ->where('surname','!=','')
                        ->where('first_name','!=','')
                        ->get();
        $filteredStudents = $students->filter(function ($student) use ($users) {
            return !$users->contains($student->stud_id);
        });

        $filteredStudents->all();
        if($filteredStudents->count()>0){
            $token = new TokenServices;
            foreach($filteredStudents as $row){
                $check = Users::where('stud_id',$row->stud_id)->first();
                if($check==NULL){
                    $token1 = $token->token(4);
                    $token2 = $token->token(4);
                    $password = Crypt::encryptString($token1.Hash::make(str_replace(' ','',mb_strtolower($row->surname))).$token2);

                    $check_users = Users::where('username',$row->stud_id)->first();

                    $sex = ($row->gender == 'Male') ? 1 : 2;

                    $get_civil_status = CivilStatuses::where('name',$row->civil_status)->first();
                    $civil_status = ($get_civil_status!=NULL) ? $get_civil_status->id : 1;
                    
                    $get_contact = DB::connection($connectionName)->table('phones')
                            ->where('stud_id',$row->stud_id)
                            ->where('contact_type','Student')
                            ->where('phone_type','Mobile')
                            ->first();
                    $contact_no = ($get_contact!=NULL) ? $get_contact->phone_no : NULL;

                    $get_email = DB::connection($connectionName)->table('contact')
                            ->where('stud_id',$row->stud_id)
                            ->first();
                    $email = ($get_email!=NULL) ? $get_email->email : NULL;
                    $dob = (date('Y-m-d', strtotime($row->date_of_birth))==$row->date_of_birth) ? $row->date_of_birth : NULL;

                    $check_role = 0;
                    $check_system = 0;

                    if($check_users!=NULL){
                        $user_id = $check_users->id;

                        Users::where('id', $user_id)
                        ->update([
                            'stud_id' => $row->stud_id,
                            'updated_by' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        _PersonalInfo::where('user_id', $user_id)
                        ->update([
                            'dob' => $dob,
                            'sex' => $sex,
                            'civil_status_id' => $civil_status,
                            'place_birth' => $row->birth_place,
                            'contact_no' => $contact_no,
                            'email' => $email,
                            'updated_by' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        $check_role_list = UsersRoleList::where('user_id',$user_id)
                            ->where('role_id',1)
                            ->first();
                        if($check_role_list!=NULL){
                            $check_role = 1;
                        }

                        $check_system_list = UsersSystems::where('user_id',$user_id)
                            ->where('system_id',1)
                            ->where('role_id',1)
                            ->first();
                        if($check_system_list!=NULL){
                            $check_system = 1;
                        }
                    }else{
                        $insert = new Users(); 
                        $insert->username = $row->stud_id;
                        $insert->password = $password;
                        $insert->level_id = 6;
                        $insert->stud_id = $row->stud_id;
                        $insert->lastname = mb_strtoupper($row->surname);
                        $insert->firstname = mb_strtoupper($row->first_name);
                        $insert->middlename = mb_strtoupper($row->middle_name);
                        $insert->extname = mb_strtoupper($row->qualifier);
                        $insert->status_id = 1;
                        $insert->user_id = 1;
                        $insert->updated_by = 1;
                        $insert->save();
                        $user_id = $insert->id;

                        $insert = new _PersonalInfo(); 
                        $insert->user_id = $user_id;
                        $insert->dob = $dob;
                        $insert->sex = $sex;
                        $insert->civil_status_id = $civil_status;
                        $insert->place_birth = $row->birth_place;
                        $insert->contact_no = $contact_no;
                        $insert->email = $email;
                        $insert->updated_by = 1;
                        $insert->save();                    
                    }

                    if($check_role==0){
                        $insert = new UsersRoleList(); 
                        $insert->user_id = $user_id;
                        $insert->role_id = 1;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                    if($check_system==0){
                        $insert = new UsersSystems(); 
                        $insert->user_id = $user_id;
                        $insert->system_id = 1;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();

                        $insert = new UsersSystemsNav(); 
                        $insert->user_id = $user_id;
                        $insert->system_nav_id = 1;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();

                        $insert = new UsersSystemsNav(); 
                        $insert->user_id = $user_id;
                        $insert->system_nav_id = 2;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();

                        $insert = new UsersSystemsNav(); 
                        $insert->user_id = $user_id;
                        $insert->system_nav_id = 3;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();

                        $insert = new UsersSystemsNav(); 
                        $insert->user_id = $user_id;
                        $insert->system_nav_id = 26;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();

                        $insert = new UsersSystemsNav(); 
                        $insert->user_id = $user_id;
                        $insert->system_nav_id = 6;
                        $insert->role_id = 1;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                }
            }
        }

    }
}
