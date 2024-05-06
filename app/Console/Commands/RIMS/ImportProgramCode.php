<?php

namespace App\Console\Commands\RIMS;

use App\Models\EducBranch;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProgramCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-program-code';

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
        $program = EducPrograms::whereDoesntHave('codes')->get();
        if($program->count()>0){
            foreach($program as $row){
                $branches = EducBranch::get();
                if($branches->count()>0){
                    foreach($branches as $branch){
                        $insert = new EducProgramsCode();
                        $insert->program_id = $row->id;
                        $insert->name = $branch->code;
                        $insert->branch_id = $branch->id;
                        $insert->status_id = 1;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                }
            }
        }
    }
}
