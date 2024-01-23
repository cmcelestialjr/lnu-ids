<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\StudentsProgram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportStudentCurriculum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-student-curriculum';

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
        $connectionCourse = 'sis_courses';
        DB::connection($connectionCourse)->getPdo();
        $connectionStudent = 'sis_student';
        DB::connection($connectionStudent)->getPdo();
        // StudentsProgram::where('program_id',15)
        //                     ->update(['program_id' => 102,
        //                             'updated_at' => date('Y-m-d H:i:s')]);
        $student_program = StudentsProgram::where('curriculum_id',NULL)->get();
        if($student_program->count()>0){
            foreach($student_program as $row){
                $stud_id = $row->info->id_no;
                $course_id = $row->program_id;
                $sy_from = $row->year_from+1;
                $sy_to = $row->year_to;

                $get_first_course = DB::connection($connectionStudent)->table('course')
                    ->where('stud_id',$stud_id)
                    ->where('course',$course_id)
                    ->where('sy','>=',1919)
                    ->orderBy('sy','ASC')
                    ->orderBy('term','ASC')
                    ->orderBy('terms','ASC')
                    ->first();
                if($get_first_course!=NULL){
                    StudentsProgram::where('id',$row->id)
                        ->update(['year_from' => $get_first_course->sy-1,
                                'updated_at' => date('Y-m-d H:i:s')]);
                }
                DB::connection($connectionCourse)->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
                $subjects = DB::connection($connectionStudent)->table('grade_log')
                    ->where('stud_id',$stud_id)
                    ->where('sy','>=',$sy_from)
                    ->where('sy','<=',$sy_to)
                    ->get();
                if($subjects->count()>0){
                    $catalog_no = [];
                    $term = [];
                    foreach($subjects as $subj){
                        $catalog_no[] = $subj->catalog_no;
                        $term[] = $subj->terms;
                    }
                    //var_dump($catalog_no);
                    $subject_curriculum = DB::connection($connectionCourse)->table('prospectus')
                            ->where('course_id',$course_id)
                            ->whereIn('catalog_no',$catalog_no)
                            // ->whereIn('term',$term)
                            ->select('prospectus_id', DB::raw('COUNT(prospectus_id) as count'))
                            ->groupBy('prospectus_id')
                            ->orderBy(DB::raw('COUNT(prospectus_id)'), 'desc')
                            ->first();
                    if ($subject_curriculum!=NULL) {
                        $curriculum_id = $subject_curriculum->prospectus_id;
                        StudentsProgram::where('id',$row->id)
                            ->update(['curriculum_id' => $curriculum_id,
                                    'updated_at' => date('Y-m-d H:i:s')]);
                    }
                    
                }
            }
        }
    }
}