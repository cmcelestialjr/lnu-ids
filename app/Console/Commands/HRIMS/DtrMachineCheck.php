<?php

namespace App\Console\Commands\HRIMS;

use App\Models\Devices;
use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\UsersDTR;
use App\Models\UsersDTRCopy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

class DtrMachineCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-machine-check';

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
        $start_time = time();

        //while (time() < $start_time + 60) { 

            // $ipaddress = '10.5.200.16';
            // $zk = new ZKTeco($ipaddress,4370);
            $query = Devices::where('device_status','Active')
                ->where('status','On')
                ->orderBy('id','DESC')
                ->get();
            if($query->count()>0){
                foreach($query as $row){
                    $ipaddress = $row->ipaddress;
                    $port = $row->port;
                    $this->data(new ZKTeco($ipaddress,$port),$ipaddress);
                }
            }
            //sleep(1);
        //}
    }
    private function data($zk,$ipaddress){
        if ($zk->connect()){

            $attendace = $zk->getAttendance();
            $recordsToInsert = [];
            $recordsCheck = 0;
            foreach($attendace as $row){
                $id_no = $row['id'];
                $state = $row['state']; //1 finger, 15 face
                $dateTime = date('Y-m-d H:i:s', strtotime($row['timestamp']));
                $type = $row['type']; //0 in, 1 out

                $record = DTRlogs::where('id_no', $id_no)
                    ->where(DB::raw("DATE_FORMAT(dateTime, '%Y-%m-%d %H:%i')"), date('Y-m-d H:i', strtotime($dateTime)))
                    ->value('id');
                if ($record === null) {
                    // $recordsToInsert[] = [
                    //     'device_id' => 0,
                    //     'id_no' => $id_no,
                    //     'state' => $state,
                    //     'dateTime' => $dateTime,
                    //     'type' => $type,
                    //     'link' => 0,
                    //     'skyhrImport' => 0,
                    //     'ipaddress' => $ipaddress,
                    //     'updated_at' => date('Y-m-d H:i:s'),
                    //     'created_at' => date('Y-m-d H:i:s')
                    // ];
                    
                    DB::beginTransaction();
                    try {
                        $insert = new DTRlogs();
                        $insert->device_id = 0;
                        $insert->id_no = $id_no;
                        $insert->state = $state;
                        $insert->dateTime = $dateTime;
                        $insert->type = $type;
                        $insert->link = 0;
                        $insert->skyhrImport = 0;
                        $insert->ipaddress = $ipaddress;
                        $insert->save();
                        $recordsCheck++;
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->error("Error: " . $e->getMessage());
                    }
                }
            }
            if($recordsCheck>0){
                $zk->clearAttendance();
            }
            //$this->info('Command executed successfully!');
            // if (!empty($recordsToInsert)) {
            //     DB::beginTransaction();
            //     try {
            //         DTRlogs::insert($recordsToInsert);
            //         $zk->clearAttendance();
            //         DB::commit();
            //     } catch (\Exception $e) {
            //         DB::rollBack();
            //         $this->error("Error during bulk insert: " . $e->getMessage());
            //     }
            // }
        }
    }
}
