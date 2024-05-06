<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-program';

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
        $connectionName = 'sis_courses';
        DB::connection($connectionName)->getPdo();

        $courses_id = EducPrograms::pluck('id')->toArray();

        $courses = DB::connection($connectionName)->table('info')
                        ->whereNotIn('course_id',$courses_id)
                        ->get();
        if($courses->count()>0){
            foreach($courses as $row){
                $status_id = ($row->offered == 'Y') ? 1 : 2;
                $department_id = ($row->college == '0' || $row->college == NULL || $row->college == '') ? NULL : $row->college;
                $department_unit = ($row->department == '0' || $row->department == NULL || $row->department == '') ? NULL : $row->department;
                if($department_id==26 || $department_id = 1){
                    $department_id = 1;
                }elseif($department_id==15 || $department_id==8){
                    $department_id = 2;
                }
                $insert = new EducPrograms();
                $insert->id = $row->course_id;
                $insert->department_id = $department_id;
                $insert->department_unit_id = $department_unit;
                $insert->name = $row->course_name;
                $insert->shorten = $row->course_abrv;
                $insert->status_id = $status_id;
                $insert->accredited = $row->accredited;
                $insert->updated_by = 1;
                $insert->save();
            }
        }

        $program_ids = EducProgramsCode::pluck('program_id')->toArray();
        $programs = EducPrograms::whereNotIn('id',$program_ids)->get();
        if($programs->count()>0){
            foreach($programs as $row){
                $insert = new EducProgramsCode();
                $insert->program_id = $row->id;
                $insert->name = 'M';
                $insert->branch_id = 1;
                $insert->status_id = $row->status_id;
                $insert->updated_by = 1;
                $insert->save();

                $insert = new EducProgramsCode();
                $insert->program_id = $row->id;
                $insert->name = 'S';
                $insert->branch_id = 2;
                $insert->status_id = $row->status_id;
                $insert->updated_by = 1;
                $insert->save();
            }
        }
    }
}
