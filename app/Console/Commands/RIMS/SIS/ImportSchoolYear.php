<?php

namespace App\Console\Commands\RIMS\SIS;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSchoolYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-sy';

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
        $connectionName = 'sis_student';
        DB::connection($connectionName)->getPdo();
    }
}
