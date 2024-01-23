<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\UsersDTR;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;

class DtrMachineCheckSpecific extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-machine-check-specific';

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
            //$ipaddress = '10.5.205.8'; //guard
            //$ipaddress = '10.5.205.23'; //youngfield
            //$ipaddress = '10.5.205.137'; //admin
            $ipaddress = '10.5.201.137'; //mis
            
            
    }
}
