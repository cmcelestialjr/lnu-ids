<?php

namespace App\Jobs;

use App\Models\Devices;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rats\Zkteco\Lib\ZKTeco;

class DevicesCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $devices = Devices::get();
            if($devices->count()>0){
                foreach($devices as $row){
                    $id = $row->id;
                    $ipaddress = $row->ipaddress;
                    $port = $row->port;
                    $zk = new ZKTeco($ipaddress,$port);
                    $status = 'Off';
                    $dateTime = NULL;
                    if ($zk->connect()){
                        $status = 'On';
                        $dateTime = date('Y-m-d H:i:s',strtotime($zk->getTime()));
                    }
                    Devices::where('id', $id)
                            ->update(['status' => $status,
                                    'dateTime' => $dateTime]);
                }
                Devices::where('id','>',0)
                        ->update(['queue' => 0]);
            }
        }catch(Exception $e) {
                
        }
    }
}
