<?php

namespace App\Console\Commands\SERVER;

use App\Models\HRPosition;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class PositionUpdateServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:position-update-server';

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

        $query = HRPosition::get();
        foreach($query as $row){
            $current_user_id = NULL;
            $user = Users::where('id',$row->current_user_id)->first();
            if($user){
                $user_server = DB::connection($connectionName)->table('users')->where('id_no',$user->id_no)->first();
                $current_user_id = $user_server->id;
            }

            DB::connection($connectionName)->table('hr_position')->updateOrInsert(
                ['id' => $row->id],
                ['item_no' => $row->item_no,
                 'name' => $row->name,
                 'shorten' => $row->shorten,
                 'salary' => $row->salary,
                 'sg' => $row->sg,
                 'step' => $row->step,
                 'level' => $row->level,
                 'code' => $row->code,
                 'type' => $row->type,
                 'level_psipop' => $row->level_psipop,
                 'ppa' => $row->ppa,
                 'gov_service' => $row->gov_service,
                 'date_created' => $row->date_created,
                 'remarks' => $row->remarks,
                 'designation_id' => $row->designation_id,
                 'emp_stat_id' => $row->emp_stat_id,
                 'fund_source_id' => $row->fund_source_id,
                 'fund_services_id' => $row->fund_services_id,
                 'role_id' => $row->role_id,
                 'type_id' => $row->type_id,
                 'status_id' => $row->status_id,
                 'sched_id' => $row->sched_id,
                 'office_id' => $row->office_id,
                 'current_user_id' => $current_user_id,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );
        }
    }
}
