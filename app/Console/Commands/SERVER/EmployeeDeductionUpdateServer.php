<?php

namespace App\Console\Commands\SERVER;

use App\Models\HRDeductionEmployee;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class EmployeeDeductionUpdateServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:employee-deduction-update-server';

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

        $query = HRDeductionEmployee::get();
        foreach($query as $row){
            $user = Users::where('id',$row->user_id)->first();
            $user_server = DB::connection($connectionName)->table('users')->where('id_no',$user->id_no)->first();
            $user_id = $user_server->id;

            DB::connection($connectionName)->table('hr_deduction_employee')->updateOrInsert(
                ['user_id' => $user_id,
                 'deduction_id' => $row->deduction_id,
                 'payroll_type_id' => $row->payroll_type_id,
                 'emp_stat_id' => $row->emp_stat_id
                ],
                ['amount' => $row->amount,
                 'amount_employer' => $row->amount_employer,
                 'percent' => $row->percent,
                 'percent_employer' => $row->percent_employer,
                 'ceiling' => $row->ceiling,
                 'date_from' => $row->date_from,
                 'date_to' => $row->date_to,
                 'remarks' => $row->remarks,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );
        }
    }
}
