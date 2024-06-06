<?php

namespace App\Console\Commands\HRIMS;

use App\Models\Devices;
use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\UsersDTR;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

class DtrMachineRemoveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-machine-remove-user';

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
        $main = Devices::find('11');
        if($main){
            $zk_main = new ZKTeco($main->ipaddress,$main->port);
            if ($zk_main->connect()){
                $users_main = $zk_main->getUser();
                $main_ids = array();
                foreach($users_main as $row){
                    $main_ids[] = $row['userid'];
                }
                $machines = Devices::where('id','!=',11)
                    ->where('status','On')
                    ->where('device_status','Active')
                    // ->where('ipaddress','10.5.205.55')
                    ->get();
                if($machines->count()>0){
                    foreach($machines as $row){
                        $zk_machine = new ZKTeco($row->ipaddress,$row->port);
                        if ($zk_machine->connect()){
                            $users_machine = $zk_machine->getUser();
                            foreach($users_machine as $row_machine){
                                if (!in_array($row_machine['userid'], $main_ids)) {
                                    if (!in_array($row_machine['userid'], $main_ids)) {
                                        $zk_machine->removeUser($row_machine['uid']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
