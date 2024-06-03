<?php

namespace App\Console\Commands\SERVER;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DtrImportServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-import-server';

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
        DB::connection($connectionName)->table('dtr_logs')->insert([
            'device_id' => 0,
            'id_no' => 230209,
            'state' => 0,
            'dateTime' => '2024-05-31 08:00:09',
            'type' => 0,
            'link' => 0,
            'skyhrImport' => 0,
            'ipaddress' => '10.5.201.137'
        ]);

        $this->info('Command executed successfully!');
    }
}


