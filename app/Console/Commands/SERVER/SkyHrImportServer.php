<?php

namespace App\Console\Commands\SERVER;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SkyHrImportServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sky-hr-import-server';

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
        $query = DB::connection($connectionName)->table('dtr_logs')
            ->where('skyhrImport',0)
            ->get();
        if($query->count()>0){
            foreach($query as $row){
                $idNo = $row->id_no;
                $dateTime = $row->dateTime;
                $type = $row->type;
                $ipaddress = $row->ipaddress;

                $employeeId = DB::connection('skyhr')->table('tblEmployees')
                    ->where('IdNo',$idNo)
                    ->value('EmployeeId');
                if($employeeId){
                    $deviceId = DB::connection('skyhr')->table('skyhr.db_owner.tblDevices')->where('IP',$ipaddress)->value('DeviceId');

                    $checkDeviceLog = DB::connection('skyhr')->table('skyhr.db_owner.tblDeviceLogs')
                        ->where('IdNo',$idNo)
                        ->where('LogDate',$dateTime.'.000')
                        ->first();
                    if($checkDeviceLog==NULL){
                        $dataToInsert = [
                            'DeviceId' => $deviceId,
                            'IdNo' => $idNo,
                            'LogDate' => $dateTime.'.000',
                            'Mode' => $type
                        ];
                        DB::connection('skyhr')->table('skyhr.db_owner.tblDeviceLogs')->insert($dataToInsert);

                        $lastInsertedId = DB::connection('skyhr')->table('skyhr.db_owner.tblDeviceLogs')
                            ->where('IdNo',$idNo)
                            ->where('LogDate',$dateTime)
                            ->orderBy('Id', 'desc')
                            ->value('Id');
                    }else{
                        $lastInsertedId = $checkDeviceLog->Id;
                    }

                    $checkEmployeeLog = DB::connection('skyhr')->table('tblEmployee_TimeLog')
                        ->where('EmployeeId',$employeeId)
                        ->where('TimeLog',$dateTime.'.000')
                        ->first();

                    if($checkEmployeeLog==NULL && $employeeId){
                        $dataToInsert = [
                            'EmployeeId' => $employeeId,
                            'TimeLog' => $dateTime.'.000',
                            'EntryType' => 0,
                            'DeviceLogId' => $lastInsertedId,
                            'Mode' => $type,
                            'DeviceReference' => 'DEVICE_ID-'.$deviceId
                        ];
                        DB::connection('skyhr')->table('tblEmployee_TimeLog')->insert($dataToInsert);
                    }

                    $data = ['skyhrImport' => 1];
                    $update = DB::connection($connectionName)->table('dtr_logs')->where('id', $row->id)
                                ->update($data);
                }
            }
        }


        $this->info('Command executed successfully!');
    }
}


