<?php

namespace App\Imports;

use App\Models\HRPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PositionImport implements ToModel, WithHeadingRow
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
        $id = $row['id'];
        $item_no = $row['item_no'];
        $name = $row['position'];
        $shorten = $row['shorten'];
        $annual = $row['salary'];
        $salary = round(($annual/12),2);
        $sg = $row['sg'];
        $step = $row['step'];
        $code = $row['code'];
        $type = $row['type'];
        $level_psipop = $row['level_psipop'];
        $ppa = $row['ppa'];
        $emp_stat_id = $row['emp_stat_id'];
        $fund_services_id = $row['fund_services_id'];
        $role_id = $row['role_id'];
        $type_id = $row['type_id'];
        $fund_source_id = $row['fund_source_id'];
        $gov_service = $row['gov_service'];
        $status_id = $row['status_id'];
        $sched_id = $row['sched_id'];

        $user = Auth::user();
        $updated_by = $user->id;

        $check = HRPosition::where('item_no',$item_no)->first();
        if($check==NULL){
            $insert = new HRPosition();
            $insert->id = $id;
            $insert->item_no = $item_no;
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->salary = $salary;
            $insert->sg = $sg;
            $insert->step = $step;
            $insert->code = $code;
            $insert->type = $type;
            $insert->level_psipop = $level_psipop;
            $insert->ppa = $ppa;
            $insert->emp_stat_id = $emp_stat_id;
            $insert->fund_source_id = $fund_source_id;
            $insert->fund_services_id = $fund_services_id;
            $insert->role_id = $role_id;
            $insert->type_id = $type_id;
            $insert->gov_service = $gov_service;
            $insert->status_id = $status_id;
            $insert->sched_id = $sched_id;
            $insert->updated_by = $updated_by;
            $insert->save();
        }

        // $connectionName = 'server';
        // DB::connection($connectionName)->getPdo();
        // $check_server = DB::connection($connectionName)->table('hr_position')->where('item_no',$item_no)->first();
        // if($check_server==NULL){
        //     DB::connection($connectionName)->table('hr_position')->insert([
        //         'id' => $id,
        //         'item_no' => $item_no,
        //         'name' => $name,
        //         'shorten' => $shorten,
        //         'salary' => $salary,
        //         'sg' => $sg,
        //         'step' => $step,
        //         'code' => $code,
        //         'type' => $type,
        //         'level_psipop' => $level_psipop,
        //         'ppa' => $ppa,
        //         'emp_stat_id' => $emp_stat_id,
        //         'fund_source_id' => $fund_source_id,
        //         'fund_services_id' => $fund_services_id,
        //         'role_id' => $role_id,
        //         'type_id' => $type_id,
        //         'gov_service' => $gov_service,
        //         'status_id' => $status_id,
        //         'sched_id' => $sched_id,
        //         'updated_by' => $updated_by,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }
    }
}
