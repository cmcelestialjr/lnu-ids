<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\UsersDTR;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

class DtrImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-import';

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
        $insert = new DTRlogs();
        $insert->device_id = 0;
        $insert->id_no = '230209';
        $insert->state = 0;
        $insert->dateTime = '2024-05-06 12:50:09';
        $insert->type = 1;
        $insert->link = 0;
        $insert->skyhrImport = 0;
        $insert->ipaddress = '10.5.201.137';
        $insert->save();

        $insert = new DTRlogs();
        $insert->device_id = 0;
        $insert->id_no = '230209';
        $insert->state = 0;
        $insert->dateTime = '2024-05-08 12:32:09';
        $insert->type = 1;
        $insert->link = 0;
        $insert->skyhrImport = 0;
        $insert->ipaddress = '10.5.201.137';
        $insert->save();

        $this->info('Command executed successfully!');
    }
}


