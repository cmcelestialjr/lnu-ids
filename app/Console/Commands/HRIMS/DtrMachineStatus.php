<?php

namespace App\Console\Commands\HRIMS;

use App\Models\Devices;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;

class DtrMachineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-machine-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()    {
        $query = Devices::where('device_status','Active')
                ->where('status','Off')
                ->orderBy('id','DESC')
                ->get();
        $x = 0;
        if($query->count()>0){
            foreach($query as $row){
                $id = $row->id;
                $ipaddress = $row->ipaddress;
                $port = $row->port;
                $no = $row->no;
                $zk = new ZKTeco($ipaddress,$port);
                if ($zk->connect()){
                    $status = 'On';
                    $dateTime = date('Y-m-d H:i:s',strtotime($zk->getTime()));
                    Devices::where('id', $id)
                        ->update(['status' => $status,
                                'no' => $no,
                                'dateTime' => $dateTime]);
                    $x++;
                }
            }
        }
        if($x>0){
            $this->info('Command executed successfully!');
        }else{
            $this->info('No command available!');
        }
    }
}
