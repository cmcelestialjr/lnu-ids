<?php

namespace App\Console\Commands\SERVER;

use App\Models\_PersonalInfo;
use App\Models\_Work;
use App\Models\Users;
use App\Models\UsersRoleList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class UserEmployeeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-employer-update-server';

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
        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();

        $del_user = Users::where('id_no', 200317)->first();
        UsersRoleList::where('user_id',$del_user->id)->delete();
        _PersonalInfo::where('user_id',$del_user->id)->delete();
        _Work::where('user_id',$del_user->id)->delete();
        Users::where('id',$del_user->id)->delete();

        $del_user = DB::connection($connectionName)->table('users')->where('id_no', 200317)->first();
        DB::connection($connectionName)->table('users_role_list')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_personal_info')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_work')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('users')->where('id',$del_user->id)->delete();

        $del_user = Users::where('id_no', 181219)->first();
        UsersRoleList::where('user_id',$del_user->id)->delete();
        _PersonalInfo::where('user_id',$del_user->id)->delete();
        _Work::where('user_id',$del_user->id)->delete();
        Users::where('id',$del_user->id)->delete();

        $del_user = DB::connection($connectionName)->table('users')->where('id_no', 181219)->first();
        DB::connection($connectionName)->table('users_role_list')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_personal_info')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_work')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('users')->where('id',$del_user->id)->delete();

        $del_user = Users::where('id_no', 30601)->first();
        UsersRoleList::where('user_id',$del_user->id)->delete();
        _PersonalInfo::where('user_id',$del_user->id)->delete();
        _Work::where('user_id',$del_user->id)->delete();
        Users::where('id',$del_user->id)->delete();

        $del_user = DB::connection($connectionName)->table('users')->where('id_no', 30601)->first();
        DB::connection($connectionName)->table('users_role_list')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_personal_info')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_work')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('users')->where('id',$del_user->id)->delete();

        $del_user = Users::where('id_no', 180919)->first();
        UsersRoleList::where('user_id',$del_user->id)->delete();
        _PersonalInfo::where('user_id',$del_user->id)->delete();
        _Work::where('user_id',$del_user->id)->delete();
        Users::where('id',$del_user->id)->delete();

        $del_user = DB::connection($connectionName)->table('users')->where('id_no', 180919)->first();
        DB::connection($connectionName)->table('users_role_list')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_personal_info')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_work')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('users')->where('id',$del_user->id)->delete();

        $del_user = Users::where('id_no', 191816)->first();
        UsersRoleList::where('user_id',$del_user->id)->delete();
        _PersonalInfo::where('user_id',$del_user->id)->delete();
        _Work::where('user_id',$del_user->id)->delete();
        Users::where('id',$del_user->id)->delete();

        $del_user = DB::connection($connectionName)->table('users')->where('id_no', 191816)->first();
        DB::connection($connectionName)->table('users_role_list')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_personal_info')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('_work')->where('user_id',$del_user->id)->delete();
        DB::connection($connectionName)->table('users')->where('id',$del_user->id)->delete();

        $query = Users::where('id_no','>',1)->get();
        foreach($query as $row){
            DB::connection($connectionName)->table('users')->updateOrInsert(
                ['id_no' => $row->id_no],
                ['username' => $row->username,
                 'password' => $row->password,
                 'level_id' => $row->level_id,
                 'lastname' => $row->lastname,
                 'firstname' => $row->firstname,
                 'middlename' => $row->middlename,
                 'extname' => $row->extname,
                 'image' => $row->image,
                 'honorific' => $row->honorific,
                 'post_nominal' => $row->post_nominal,
                 'status_id' => $row->status_id,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );

            $query_user = _PersonalInfo::where('user_id',$row->id)->first();
            $user = DB::connection($connectionName)->table('users')->where('id_no',$row->id_no)->first();
            $user_id = $user->id;
            DB::connection($connectionName)->table('_personal_info')->updateOrInsert(
                ['user_id' => $user_id],
                ['nickname' => $query_user->nickname,
                 'dob' => $query_user->dob,
                 'place_birth' => $query_user->place_birth,
                 'sex' => $query_user->sex,
                 'civil_status_id' => $query_user->civil_status_id,
                 'height' => $query_user->height,
                 'weight' => $query_user->weight,
                 'blood_type_id' => $query_user->blood_type_id,
                 'religion_id' => $query_user->religion_id,
                 'bank_account_no' => $query_user->bank_account_no,
                 'tin_no' => $query_user->tin_no,
                 'email' => $query_user->email,
                 'email_official' => $query_user->email_official,
                 'updated_by' => $query_user->updated_by,
                 'updated_at' => $query_user->updated_at,
                 'created_at' => $query_user->created_at
                ]
            );

            $query_work = _Work::where('user_id',$row->id)->get();
            if($query_work->count()>0){
                foreach($query_work as $row_work){
                    DB::connection($connectionName)->table('_work')->updateOrInsert(
                        ['user_id' => $user_id,
                         'date_from' => $row_work->date_from],
                        ['position_id' => $row_work->position_id,
                             'designation_id' => $row_work->designation_id,
                             'credit_type_id' => $row_work->credit_type_id,
                             'role_id' => $row_work->role_id,
                             'emp_stat_id' => $row_work->emp_stat_id,
                             'fund_source_id' => $row_work->fund_source_id,
                             'fund_services_id' => $row_work->fund_services_id,
                             'office_id' => $row_work->office_id,
                             'date_to' => $row_work->date_to,
                             'position_title' => $row_work->position_title,
                             'position_shorten' => $row_work->position_shorten,
                             'designation_title' => $row_work->designation_title,
                             'designation_shorten' => $row_work->designation_shorten,
                             'office' => $row_work->office,
                             'salary' => $row_work->salary,
                             'sg' => $row_work->sg,
                             'step' => $row_work->step,
                             'status' => $row_work->status,
                             'gov_service' => $row_work->gov_service,
                             'lwop' => $row_work->lwop,
                             'oic' => $row_work->oic,
                             'separation' => $row_work->separation,
                             'date_separation' => $row_work->date_separation,
                             'cause' => $row_work->cause,
                             'remarks' => $row_work->remarks,
                             'docs' => $row_work->docs,
                             'type_id' => $row_work->type_id,
                             'updated_by' => $row_work->updated_by,
                             'updated_at' => $row_work->updated_at,
                             'created_at' => $row_work->created_at
                        ]
                    );
                }
            }

        }
    }
}
