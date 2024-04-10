<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\StudentsInfo;
use App\Models\Users;
use Illuminate\Console\Command;

class ImportStudentInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-student-info';

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
        //Steps in importing Student from sys
        //1. ImportStudent
        //2. ImportStudentProgram
        //3. ImportStudentCurriculum
        //4. ImportStudentInfo
        
        $student_program = Users::where('stud_id','!=',NULL)
            ->get();
        if($student_program->count()>0){
            foreach($student_program as $row){
                if($row->student_program_latest!=NULL){
                    $check = StudentsInfo::where('user_id',$row->id)->first();
                    if($check==NULL){
                        $insert = new StudentsInfo(); 
                        $insert->user_id = $row->id;
                        $insert->id_no = $row->stud_id;
                        $insert->program_id = $row->student_program_latest->program_id;                    
                        $insert->program_code_id = $row->student_program_latest->program_code_id;
                        $insert->program_level_id = $row->student_program_latest->program_level_id;
                        $insert->curriculum_id = $row->student_program_latest->curriculum_id;
                        $insert->student_status_id = $row->student_program_latest->student_status_id;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                }
            }   
        }
    }
}