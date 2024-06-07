<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\_Work;
use App\Models\HRPosition;
use App\Models\Users;
use App\Models\UsersRoleList;
use App\Models\UsersSystems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
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

    public function model(array $row)
    {
        $id_no = $row['id_no'];
        $username = $row['username'];
        $status_id = 1;
        $emp_status_id = 1;

        $user = Auth::user();
        $updated_by = $user->id;

        $check = Users::where('id_no',$id_no)->first();
        if($check){
            if($check->id>1){
                Users::where('id_no', $id_no)
                    ->update([
                        'username' => $username,
                        'status_id' => $status_id,
                        'emp_status_id' => $emp_status_id
                    ]);
                UsersRoleList::where('user_id', $check->id)->delete();
                _Work::where('user_id', $check->id)->delete();
                $this->insert($row,$check->id,$updated_by);
            }
        }else{
            Users::insert([
                'username' => $username,
                'level_id' => 6,
                'id_no' => $id_no,
                'status_id' => $status_id,
                'emp_status_id' => $emp_status_id,
                'user_id' => $updated_by,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $get_user = Users::where('id_no',$id_no)->first();
            $this->insert($row,$get_user->id,$updated_by);
        }

        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();

        $check = DB::connection($connectionName)->table('users')->where('id_no',$id_no)->first();
        if($check){
            if($check->id>1){
                DB::connection($connectionName)->table('users')->where('id_no', $id_no)
                    ->update([
                        'username' => $username,
                        'status_id' => $status_id,
                        'emp_status_id' => $emp_status_id
                    ]);
                DB::connection($connectionName)->table('users_role_list')->where('user_id', $check->id)->delete();
                DB::connection($connectionName)->table('_work')->where('user_id', $check->id)->delete();
                $this->insertServer($row,$check->id,$updated_by);
            }
        }else{
            DB::connection($connectionName)->table('users')->insert([
                'username' => $username,
                'level_id' => 6,
                'id_no' => $id_no,
                'status_id' => $status_id,
                'emp_status_id' => $emp_status_id,
                'user_id' => $updated_by,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $get_user = DB::connection($connectionName)->table('users')->where('id_no',$id_no)->first();
            $this->insertServer($row,$get_user->id,$updated_by);
        }
    }
    private function insert($row,$user_id,$updated_by)
    {
        $sex = $row['sex'];
        $dob = str_replace("'","",$row['dob']);
        $tin_no = str_replace("'","",$row['tin_no']);
        $role_id = $row['role_id'];
        $position_id = $row['position_id'];
        $annual = $row['salary'];
        $salary = round(($annual/12),2);
        $sg = $row['sg'];
        $step = $row['step'];
        $emp_stat_id = $row['emp_stat_id'];
        $fund_source_id = $row['fund_source_id'];
        $fund_services_id = $row['fund_services_id'];
        $date_from = $row['date_from'];
        $gov_service = $row['gov_service'];
        $type_id = $row['type_id'];
        $remarks = $row['remarks'];
        if($dob==''){
            $dob = NULL;
        }
        if($remarks==''){
            $remarks = NULL;
        }
        if($tin_no==''){
            $tin_no = NULL;
        }
        UsersRoleList::insert([
                'user_id' => $user_id,
                'role_id' => $role_id,
                'emp_stat' => $emp_stat_id,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        _PersonalInfo::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'dob' => $dob,
                    'sex' => $sex,
                    'tin_no' => $tin_no,
                    'updated_by' => $updated_by,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
        );
        $position_info = HRPosition::where('id',$position_id)->first();
        if($position_info){
            _Work::insert([
                'user_id' => $user_id,
                'position_id' => $position_id,
                'role_id' => $role_id,
                'emp_stat_id' => $emp_stat_id,
                'fund_source_id' => $fund_source_id,
                'fund_services_id' => $fund_services_id,
                'date_from' => $date_from,
                'date_to' => 'present',
                'position_title' => $position_info->name,
                'position_shorten' => $position_info->shorten,
                'office' => 'LNU',
                'salary' => $salary,
                'sg' => $sg,
                'step' => $step,
                'status' => 1,
                'gov_service' => $gov_service,
                'remarks' => $remarks,
                'type_id' => $type_id,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
    private function insertServer($row,$user_id,$updated_by)
    {
        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();

        $sex = $row['sex'];
        $dob = str_replace("'","",$row['dob']);
        $tin_no = str_replace("'","",$row['tin_no']);
        $role_id = $row['role_id'];
        $position_id = $row['position_id'];
        $annual = $row['salary'];
        $salary = round(($annual/12),2);
        $sg = $row['sg'];
        $step = $row['step'];
        $emp_stat_id = $row['emp_stat_id'];
        $fund_source_id = $row['fund_source_id'];
        $fund_services_id = $row['fund_services_id'];
        $date_from = $row['date_from'];
        $gov_service = $row['gov_service'];
        $type_id = $row['type_id'];
        $remarks = $row['remarks'];
        if($dob==''){
            $dob = NULL;
        }
        if($remarks==''){
            $remarks = NULL;
        }
        if($tin_no==''){
            $tin_no = NULL;
        }
        DB::connection($connectionName)->table('users_role_list')->insert([
                'user_id' => $user_id,
                'role_id' => $role_id,
                'emp_stat' => $emp_stat_id,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $existingRecord = DB::connection($connectionName)->table('_personal_info')
            ->where('user_id', $user_id)
            ->first();
        if ($existingRecord) {
            DB::connection($connectionName)->table('_personal_info')
                ->where('user_id', $user_id)
                ->update([
                    'dob' => $dob,
                    'sex' => $sex,
                    'tin_no' => $tin_no,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            DB::connection($connectionName)->table('_personal_info')->insert([
                'user_id' => $user_id,
                'dob' => $dob,
                'sex' => $sex,
                'tin_no' => $tin_no,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $position_info = DB::connection($connectionName)->table('hr_position')->where('id',$position_id)->first();
        if($position_info){
            DB::connection($connectionName)->table('_work')->insert([
                'user_id' => $user_id,
                'position_id' => $position_id,
                'role_id' => $role_id,
                'emp_stat_id' => $emp_stat_id,
                'fund_source_id' => $fund_source_id,
                'fund_services_id' => $fund_services_id,
                'date_from' => $date_from,
                'date_to' => 'present',
                'position_title' => $position_info->name,
                'position_shorten' => $position_info->shorten,
                'office' => 'LNU',
                'salary' => $salary,
                'sg' => $sg,
                'step' => $step,
                'status' => 1,
                'gov_service' => $gov_service,
                'remarks' => $remarks,
                'type_id' => $type_id,
                'updated_by' => $updated_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
