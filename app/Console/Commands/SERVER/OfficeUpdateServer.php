<?php

namespace App\Console\Commands\SERVER;

use App\Models\Office;
use App\Models\OfficeType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class OfficeUpdateServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:office-update-server';

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

        $query = OfficeType::get();
        foreach($query as $row){
            DB::connection($connectionName)->table('office_type')->updateOrInsert(
                ['id' => $row->id],
                ['name' => $row->name,
                 'shorten' => $row->shorten,
                 'level' => $row->level,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );
        }

        $query = Office::get();
        foreach($query as $row){
            DB::connection($connectionName)->table('office')->updateOrInsert(
                ['id' => $row->id],
                ['name' => $row->name,
                 'shorten' => $row->shorten,
                 'office_type_id' => $row->office_type_id,
                 'parent_office_id' => $row->parent_office_id,
                 'icon' => $row->icon,
                 'updated_by' => $row->updated_by,
                 'updated_at' => $row->updated_at,
                 'created_at' => $row->created_at
                ]
            );
        }
    }
}
