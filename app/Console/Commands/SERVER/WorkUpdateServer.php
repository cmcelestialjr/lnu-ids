<?php

namespace App\Console\Commands\SERVER;

use App\Models\_Work;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class WorkUpdateServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:work-update-server';

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

        $query = _Work::get();
        foreach($query as $row){
            $user = Users::where('id',$row->user_id)->first();
            $user_server = DB::connection($connectionName)->table('users')->where('id_no',$user->id_no)->first();
            $user_id = $user_server->id;

            DB::connection($connectionName)->table('_work')->updateOrInsert(
                ['user_id' => $user_id,
                 'date_from' => $row->date_from
                ],
                ['position_id' => $row->position_id,
                 'designation_id' => $row->designation_id,
                 'credit_type_id' => $row->credit_type_id,
                 'emp_stat_id' => $row->emp_stat_id,
                 'fund_source_id' => $row->fund_source_id,
                 'fund_services_id' => $row->fund_services_id,
                 'office_id' => $row->office_id,
                 'date_to' => $row->date_to,
                 'position_title' => $row->position_title,
                 'position_shorten' => $row->position_shorten,
                 'designation_title' => $row->designation_title,
                 'designation_shorten' => $row->designation_shorten,
                 'office' => $row->office,
                 'salary' => $row->salary,
                 'sg' => $row->sg,
                 'step' => $row->step,
                 'status' => $row->status,
                 'gov_service' => $row->gov_service,
                 'lwop' => $row->lwop,
                 'oic' => $row->oic,
                 'separation' => $row->separation,
                 'date_separation' => $row->date_separation,
                 'cause' => $row->cause,
                 'remarks' => $row->remarks,
                 'docs' => $row->docs,
                 'type_id' => $row->type_id,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );
        }
    }
}
