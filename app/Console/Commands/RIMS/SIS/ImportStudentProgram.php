<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\EducCourses;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\StudentsProgram;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportStudentProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-student-program';

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

        $student_program = StudentsProgram::select('user_id')->groupBy('user_id')->pluck('user_id')->toArray();
        $stud_ids = Users::whereNotIn('id',$student_program)
            ->where('stud_id','!=',NULL)
            ->orderBy('id','ASC')
            ->pluck('stud_id')
            ->toArray();
        
        DB::connection($connectionName)->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $student_courses = DB::connection($connectionName)->table('course')
                                ->whereIn('stud_id',$stud_ids)
                                ->where('course','>',0)
                                ->where('sy','>=',1919)
                                ->groupBy('stud_id')
                                ->groupBy('course')
                                ->get();
        if($student_courses->count()>0){
            foreach($student_courses as $row){
                $student = Users::where('stud_id',$row->stud_id)->first();
                $program = EducPrograms::where('id',$row->course)->first();
                $program_code = EducProgramsCode::where('program_id',$program->id)->orderBy('branch_id','ASC')->first();
                $get_first_course = DB::connection($connectionName)->table('course')
                    ->where('stud_id',$row->stud_id)
                    ->where('course',$row->course)
                    ->where('sy','>=',1919)
                    ->orderBy('sy','ASC')
                    ->orderBy('term','ASC')
                    ->orderBy('terms','ASC')
                    ->first();
                if($get_first_course!=NULL){
                    $get_latest_course = DB::connection($connectionName)->table('course')
                        ->where('stud_id',$row->stud_id)
                        ->where('course',$row->course)
                        ->where('sy','>=',1919)
                        ->orderBy('sy','DESC')
                        ->orderBy('term','DESC')
                        ->orderBy('terms','DESC')
                        ->first();
                    if($get_latest_course!=NULL){
                        $date_graduate = (date('Y-m-d', strtotime($get_latest_course->graduated_on))==$get_latest_course->graduated_on) ? $get_latest_course->graduated_on : NULL;                
                        if ($get_first_course->sy == $get_latest_course->sy) {
                            $student_status_id = 1; 
                        } else {
                            $student_status_id = 2; 
                        }
                        if ($get_latest_course->status=='graduated') {
                            $student_status_id = 7; 
                        }
                        $get_latest_course_sy = $get_latest_course->sy;
                        $remarks = $get_latest_course->remarks;

                        $insert = new StudentsProgram(); 
                        $insert->user_id = $student->id;
                        $insert->program_id = $row->course;
                        $insert->program_level_id = $program->program_level_id;
                        $insert->program_code_id = $program_code->id;
                        $insert->program_name = $program->name;
                        $insert->program_shorten = $program->shorten;
                        $insert->year_from = $get_first_course->sy-1;
                        $insert->year_to = $get_latest_course_sy;
                        $insert->from_school = 'Leyte Normal University';
                        $insert->student_status_id = $student_status_id;
                        $insert->remarks = $remarks;
                        $insert->date_graduate = $date_graduate;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                }
            }   
        }
        
    }
}