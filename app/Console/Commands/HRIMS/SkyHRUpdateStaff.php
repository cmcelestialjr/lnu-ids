<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\UsersDTR;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

class SkyHRUpdateStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sky-update-staff';

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
        $query = DTRlogs::where('id_no',240204)->get();
        if($query->count() > 0){
            foreach($query as $row){
                $idNo = $row->id_no;
                $dateTime = $row->dateTime;
                $type = $row->type;
                $ipaddress = $row->ipaddress;

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

                $employeeId = DB::connection('skyhr')->table('tblEmployees')
                    ->where('IdNo',$idNo)
                    ->value('EmployeeId');

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
            }
            $this->info('Command executed successfully!');
        }
    }
}
