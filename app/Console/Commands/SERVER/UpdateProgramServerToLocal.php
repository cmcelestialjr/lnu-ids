<?php

namespace App\Console\Commands\SERVER;

use App\Models\EducPrograms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class UpdateProgramServerToLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-program-server-local';

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
        $programs = DB::connection($connectionName)->table('educ_programs')->get();
        foreach($programs as $row){
            $check = EducPrograms::where('id',$row->id)->first();
            if($check){
                $update = EducPrograms::find($row->id);
                $update->department_id = $row->department_id;
                $update->department_unit_id = $row->department_unit_id;
                $update->program_level_id = $row->program_level_id;
                $update->name = $row->name;
                $update->shorten = $row->shorten;
                $update->updated_by = $row->updated_by;
                $update->created_at = $row->created_at;
                $update->updated_at = $row->updated_at;
            }else{
                $update = new EducPrograms;
                $update->id = $row->id;
                $update->department_id = $row->department_id;
                $update->department_unit_id = $row->department_unit_id;
                $update->program_level_id = $row->program_level_id;
                $update->name = $row->name;
                $update->shorten = $row->shorten;
                $update->updated_by = $row->updated_by;
                $update->created_at = $row->created_at;
                $update->updated_at = $row->updated_at;
            }
            $update->save();
        }
    }
}
