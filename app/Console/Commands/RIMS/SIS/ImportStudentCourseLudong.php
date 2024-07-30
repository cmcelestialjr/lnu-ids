<?php

namespace App\Console\Commands\RIMS\SIS;

use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\LudongCollegeSubjects;
use App\Models\LudongCollegeSubjectsExt;
use App\Models\LudongMark;
use App\Models\LudongMarkCredit;
use App\Models\LudongMarks;
use App\Models\LudongSchools;
use App\Models\StudentsCourses;
use App\Models\StudentsCoursesCredit;
use App\Models\StudentsProgram;
use App\Models\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportStudentCourseLudong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sis-import-student-course-ludong';

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
        //4. ImportStudentCourse

        $connectionName = 'sis_student';
        $connectionCollege = 'sis_college';
        $connectionTools = 'sis_tools';
        DB::connection($connectionName)->getPdo();
        DB::connection($connectionCollege)->getPdo();
        DB::connection($connectionTools)->getPdo();

        $courses = DB::connection($connectionName)->table('mark')
            // ->where('stud_id','2202681')
            //->where('submitted_on','>=','2024-07-24 10:10:47')
            ->where('sy','2023')
            //->where('term',1)
            ->whereIn('term',[1,2,3])
            ->where('terms','2')
            ->where('submitted_on','>=','2023-02-15 00:00:00')
            ->where('submitted_on','<=','2023-02-29 00:00:00')
            ->orderBy('sy','ASC')
            ->orderBy('term','ASC')
            ->orderBy('terms','ASC')
            ->get();
        if($courses->count()>0){
            foreach($courses as $course){
                $stud_id = $course->stud_id;
                $sy = $course->sy-1;
                $course_code = $course->catalog_no;
                $course_code_id = $course->catalog_id;
                $grade_period_id = $course->term;
                $units = $course->units;
                $school_id = $course->school;
                $school_name = 'Leyte Normal University';
                $course_desc = NULL;

                $student = Users::where('stud_id',$stud_id)->first();
                if($student){
                    $user_id = $student->id;
                    if($school_id==528){
                        $school_name = 'Leyte Normal University';
                    }else{
                        $getSchool = DB::connection($connectionTools)->table('schools')->where('school_id',$school_id)->first();
                        if($getSchool){
                            $school_name = $getSchool->school_name;
                        }
                    }
                    if($course->term==3){
                        $grade_period_id = 4;
                    }
                    if($course->catalog_id>0){
                        $getSubject = DB::connection($connectionCollege)->table('subjects_ext')->where('catalog_id',$course_code_id)->first();
                        if($getSubject){
                            $course_desc = $getSubject->desc_title;
                        }
                    }else{
                        $getSubject = DB::connection($connectionCollege)->table('subjects')->where('catalog_no',$course_code)->first();
                        if($getSubject){
                            $course_desc = $getSubject->description;
                            $units = $getSubject->load_units;
                        }
                    }

                    $student_program_id = NULL;
                    $curriculum_id = NULL;
                    $program_level_id = NULL;
                    $program_name = NULL;
                    $program_shorten = NULL;
                    $grade = NULL;
                    $grade_by = NULL;
                    $graded_by_id_no = NULL;
                    $graded_at = NULL;
                    $graded_from = NULL;
                    $student_course_status_id = NULL;
                    $remarks = NULL;
                    $course_id = NULL;
                    $grade_level_id = NULL;

                    $studentProgram = StudentsProgram::with('program_info')
                        ->where('user_id',$user_id)
                        ->where('year_from','<=',$sy)
                        ->orderBy('year_from','DESC')
                        ->first();
                    if($studentProgram){
                        $curriculum_id = $studentProgram->curriculum_id;
                        $student_program_id = $studentProgram->id;
                        $program_level_id = $studentProgram->program_level_id;
                        $program_name = $studentProgram->program_info->name;
                        $program_shorten = $studentProgram->program_info->shorten;

                        $getCourse = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('code',$course_code)
                            ->first();
                        if($getCourse){
                            $course_id = $getCourse->id;
                            $grade_level_id = $getCourse->grade_level_id;
                            if($units==NULL || $units=='' || $units<=0){
                                $units = $getCourse->units;
                            }
                        }
                    }else{
                        $getCourse = EducCourses::with('curriculum.programs')
                            ->where('code',$course_code)
                            ->first();
                        if($getCourse){
                            $program_level_id = $getCourse->curriculum->programs->program_level_id;
                            if($units==NULL || $units=='' || $units<=0){
                                $units = $getCourse->units;
                            }
                        }
                    }

                    $getGradeBy = DB::connection($connectionName)->table('marks')
                        ->where('stud_id',$stud_id)
                        ->where('sy',$course->sy)
                        ->where('term',$course->term)
                        ->where('terms',$course->terms)
                        ->where('catalog_no',$course->catalog_no)
                        ->first();
                    if($getGradeBy){
                        $graded_by_id_no = $getGradeBy->submitted_by;
                        $graded_at = $getGradeBy->submitted_on;
                        $graded_from = $getGradeBy->submitted_from;
                        $getUserGradeBy = Users::where('id_no',$graded_by_id_no)->first();
                        if($getUserGradeBy){
                            $grade_by = $getUserGradeBy->id;
                        }
                    }

                    if($course->retake!='' || $course->retake!=NULL){
                        $getGrade = $this->retake($course->retake);
                        if($getGrade=='NotExists'){
                            $getGrade = $this->grades($course->retake);
                        }
                    }else{
                        $getGrade = $this->grades($course->grade);
                    }
                    if($getGrade=='NotExists'){
                        $remarks = 'NotExists';
                    }else{
                        $exp = explode('_',$getGrade);
                        $grade = $exp[0];
                        $student_course_status_id = $exp[1];
                    }
                    if (!is_numeric($units)) {
                        $units = NULL;
                    }

                    $courseCheck = StudentsCourses::where('course_code',$course_code)
                        ->where('grade_period_id',$grade_period_id)
                        ->where('user_id',$user_id)
                        ->where('year_from',$sy)
                        ->where('year_to',$sy+1)
                        ->first();
                    if($courseCheck==NULL){
                        $insert = new StudentsCourses();
                        $insert->student_program_id = $student_program_id;
                        $insert->user_id = $user_id;
                        $insert->stud_id = $course->stud_id;
                        $insert->course_id = $course_id;
                        $insert->grade_level_id = $grade_level_id;
                        $insert->program_level_id = $program_level_id;
                        $insert->grade_period_id = $grade_period_id;
                        $insert->course_code = $course_code;
                        $insert->course_desc = $course_desc;
                        $insert->course_units = $units;
                        $insert->program_name = $program_name;
                        $insert->program_shorten = $program_shorten;
                        $insert->year_from = $sy;
                        $insert->year_to = $sy+1;
                        $insert->school_name = $school_name;
                        $insert->type_id = 1;
                        $insert->grade = $grade;
                        $insert->final_grade = $grade;
                        $insert->graded_by = $grade_by;
                        $insert->graded_by_id_no = $graded_by_id_no;
                        $insert->graded_at = $graded_at;
                        $insert->graded_from = $graded_from;
                        $insert->student_course_status_id = $student_course_status_id;
                        $insert->remarks = $remarks;
                        $insert->updated_by = 1;
                        $insert->save();
                        $student_course_id = $insert->id;

                        $credit = DB::connection($connectionName)->table('mark_credit')
                            ->where('stud_id',$course->stud_id)
                            ->where('sy',$course->sy)
                            ->where('term',$course->term)
                            ->where('terms',$course->terms)
                            ->where('catalog_no',$course->catalog_no)
                            ->first();
                        if($credit){
                            $course_code = $credit->credit_as;
                            $course_id = NULL;

                            $getCourse = EducCourses::where('curriculum_id',$curriculum_id)
                                ->where('code',$course_code)
                                ->first();
                            if($getCourse){
                                $course_id = $getCourse->id;
                            }

                            $insert = new StudentsCoursesCredit();
                            $insert->student_course_id = $student_course_id;
                            $insert->user_id = $user_id;
                            $insert->course_id = $course_id;
                            $insert->course_code = $course_code;
                            $insert->save();

                            StudentsCourses::where('id', $student_course_id)
                            ->update([
                                'type_id' => 2
                            ]);
                        }
                    }else{
                        if (!is_numeric($units)) {
                            $units = NULL;
                        }
                        if($courseCheck->course_units!=NULL){
                            $units = $courseCheck->course_units;
                        }
                        StudentsCourses::where('id', $courseCheck->id)
                            ->update([
                                'course_desc' => $course_desc,
                                'course_units' => $units,
                                'grade_level_id' => $grade_level_id,
                                'grade' => $grade,
                                'final_grade' => $grade,
                                'graded_by' => $grade_by,
                                'graded_by_id_no' => $graded_by_id_no,
                                'graded_at' => $graded_at,
                                'graded_from' => $graded_from,
                                'student_course_status_id' => $student_course_status_id,
                                'remarks' => $remarks
                            ]);
                    }
                }
            }
        }

    }

    private function grades($grade){
        $grade = str_replace("\\", "", $grade);
        $grades = array('2.2'=>'2.2_1',
        '2.0'=>'2_1',
        '2.8'=>'2.8_1',
        '2.9'=>'2.9_1',
        '2.1'=>'2.1_1',
        '2.4'=>'2.4_1',
        '1.8'=>'1.8_1',
        '2.3'=>'2.3_1',
        '2.7'=>'2.7_1',
        '2.5'=>'2.5_1',
        '4.0'=>'4_11',
        '1.9'=>'1.9_1',
        '2.6'=>'2.6_1',
        '1.6'=>'1.6_1',
        '1.5'=>'1.5_1',
        '1.3'=>'1.3_1',
        '1.7'=>'1.7_1',
        '3.0'=>'3_1',
        '5.0'=>'5_11',
        '1.2'=>'1.2_1',
        'INC'=>'_8',
        '1.4'=>'1.4_1',
        'DR'=>'_9',
        '1.1'=>'1.1_1',
        '1.0'=>'1_1',
        ''=>'_8',
        '4'=>'4_11',
        '2.25'=>'2.25_1',
        'NG'=>'_8',
        'NA'=>'_8',
        '1.75'=>'1.75_1',
        '2.75'=>'2.75_1',
        '2.65'=>'2.65_1',
        '2.85'=>'2.85_1',
        '3.1'=>'3.1_1',
        '2.15'=>'2.15_1',
        '2.32'=>'2.32_1',
        '1.25'=>'1.25_1',
        '1.6.'=>'1.6_1',
        'Psd'=>'_1',
        '.2.1'=>'2.1_1',
        'DR.'=>'_9',
        'WD'=>'_12',
        '.'=>'_8',
        'INC.'=>'_8',
        '3.0T'=>'3_1',
        'DRP'=>'_9',
        '2.28'=>'2.28_1',
        '3.2'=>'3.2_1',
        '2.0.'=>'2_1',
        'P'=>'_1',
        '3.9'=>'3.9_11',
        'NE'=>'_8',
        'PASSED'=>'_1',
        '2'=>'2_1',
        '3'=>'3_1',
        '5'=>'5_11',
        '-'=>'_8',
        '2.24'=>'2.24_1',
        '1'=>'1_1',
        '1.49'=>'1.49_1',
        '1.15'=>'1.15_1',
        '2.73'=>'2.73_1',
        '4.5'=>'4.5_11',
        '1.96'=>'1.96_1',
        '4.2'=>'4.2_11',
        '2.13'=>'2.13_1',
        '2.06'=>'2.06_1',
        '3.5'=>'3.5_1',
        '1.56'=>'1.56_1',
        '2.26'=>'2.26_1',
        '1.89'=>'1.89_1',
        '3.3'=>'3.3_1',
        '1.66'=>'1.66_1',
        '1.81'=>'1.81_1',
        '2.27'=>'2.27_1',
        '1.73'=>'1.73_1',
        '1.72'=>'1.72_1',
        '2.04'=>'2.04_1',
        '1.67'=>'1.67_1',
        'INP'=>'_8',
        '1.98'=>'1.98_1',
        '1.74'=>'1.74_1',
        '1.92'=>'1.92_1',
        '1.50'=>'1.5_1',
        '2.50'=>'2.5_1',
        '1.30'=>'1.3_1',
        '3.00'=>'3_1',
        '2-'=>'2_1',
        '1.47'=>'1.47_1',
        '91.00'=>'1.4_1',
        '1.00'=>'1_1',
        '3.7'=>'3.7_1',
        '3.4'=>'3.4_1',
        '4.1'=>'4.1_11',
        'NL'=>'_8',
        '4.4'=>'4.4_11',
        '3.8'=>'3.8_11',
        '2.36'=>'2.36_1',
        'T'=>'_2',
        'CRDTD'=>'_1',
        '1.44'=>'1.44_1',
        '1.12'=>'1.12_1',
        '1.37'=>'1.37_1',
        '1.65'=>'1.65_1',
        '2.52'=>'2.52_1',
        'HP'=>'1.4_1',
        'LP'=>'_8',
        '12'=>'1.2_1',
        '2.45'=>'2.45_1',
        '1.91'=>'1.91_1',
        '1.21'=>'1.21_1',
        '2.54'=>'2.54_1',
        '1.58'=>'1.58_1',
        '1.78'=>'1.78_1',
        '22'=>'2.2_1',
        '1.46'=>'1.46_1',
        '1.63'=>'1.63_1',
        '1.53'=>'1.53_1',
        '2..'=>'2_1',
        '1.125'=>'1.125_1',
        '1.625'=>'1.625_1',
        '1.375'=>'1.375_1',
        '1.69'=>'1.69_1',
        '32'=>'3.2_1',
        '99'=>'1_1',
        '1.35'=>'1.35_1',
        '1.33'=>'1.33_1',
        '1.71'=>'1.71_1',
        '27'=>'2.7_1',
        '2.55'=>'2.55_1',
        '2.05'=>'2.05_1',
        'DROP'=>'_9',
        '2.95'=>'2.95_1',
        '2.69'=>'2.69_1',
        '2.35'=>'2.35_1',
        '0'=>'_11',
        '2.75'=>'2.75_1',
        '2.25'=>'2.25_1',
        '1.75'=>'1.75_1',
        '2.7'=>'2.7_1',
        '2.5'=>'2.5_1',
        '0.0000'=>'_11',
        '4.8'=>'4.8_11',
        '2.5T'=>'2.5_1',
        '2.11'=>'2.11_1',
        '2.17'=>'2.17_1',
        '1.45'=>'1.45_1',
        '3.6'=>'3.6_11',
        '1.85'=>'1.85_1',
        '1.95'=>'1.95_1',
        '1.51'=>'1.51_1',
        '2.00'=>'2_1',
        '2.60'=>'2.6_1',
        '1.24'=>'1.24_1',
        '1.18'=>'1.18_1',
        '2.53'=>'2.53_1',
        '1994'=>'1.994_1',
        '1.55'=>'1.55_1',
        '3..'=>'3_1',
        '4.3'=>'4.3_11',
        '2.42'=>'2.42_1',
        '2.59'=>'2.59_1',
        '2.22'=>'2.22_1',
        '2.12'=>'2.12_1',
        '1.05'=>'1.05_1',
        'NFE'=>'_8',
        'TW'=>'_2',
        '2.21'=>'2.21_1',
        '1.93'=>'1.93_1',
        '1,8'=>'1.8_1',
        '2.33'=>'2.33_1',
        '2.31'=>'2.31_1',
        '2.16'=>'2.16_1',
        '1.88'=>'1.88_1',
        '2.14'=>'2.14_1',
        '2.51'=>'2.51_1',
        '2.56'=>'2.56_1',
        '2.41'=>'2.41_1',
        '2.62'=>'2.62_1',
        '2.58'=>'2.58_1',
        '1.83'=>'1.83_1',
        '2.37'=>'2.37_1',
        '1.99'=>'1.99_1',
        '2.34'=>'2.34_1',
        '2.48'=>'2.48_1',
        '2.29'=>'2.29_1',
        '2.49'=>'2.49_1',
        '1.79'=>'1.79_1',
        '2.89'=>'2.89_1',
        '2.81'=>'2.81_1',
        '2.18'=>'2.18_1',
        '2.46'=>'2.46_1',
        '2.91'=>'2.91_1',
        '2.64'=>'2.64_1',
        '2.92'=>'2.92_1',
        '2.08'=>'2.08_1',
        '2.86'=>'2.86_1',
        '2.79'=>'2.79_1',
        '2.66'=>'2.66_1',
        '2.78'=>'2.78_1',
        '2.39'=>'2.39_1',
        '2.83'=>'2.83_1',
        '2.63'=>'2.63_1',
        '2.94'=>'2.94_1',
        '1.97'=>'1.97_1',
        '2.44'=>'2.44_1',
        '2.03'=>'2.03_1',
        '2.74'=>'2.74_1',
        '2.23'=>'2.23_1',
        '1.61'=>'1.61_1',
        '2.47'=>'2.47_1',
        '2.07'=>'2.07_1',
        '2.19'=>'2.19_1',
        '2.43'=>'2.43_1',
        '1.76'=>'1.76_1',
        '2.68'=>'2.68_1',
        '1.86'=>'1.86_1',
        '2.01'=>'2.01_1',
        '2.38'=>'2.38_1',
        '1.54'=>'1.54_1',
        'Dropped'=>'_9',
        '89.0'=>'1.6_1',
        'DNA'=>'_9',
        '87.0'=>'1.8_1',
        '2.67'=>'2.67_1',
        '2009'=>'2.009_1',
        '1.22'=>'1.22_1',
        '1.36'=>'1.36_1',
        'IP'=>'_8',
        'HPsd'=>'_3',
        'Audit'=>'_8',
        '1.31'=>'1.31_1',
        '1.42'=>'1.42_1',
        '1.48'=>'1.48_1',
        '1.34'=>'1.34_1',
        '1.68'=>'1.68_1',
        '96'=>'1_1',
        '84'=>'2.1_1',
        '9'=>'5_11',
        '8'=>'5_11',
        '1.52'=>'1.52_1',
        '2.40'=>'2.4_1',
        '1.60'=>'1.6_1',
        '97.0'=>'1_1',
        '92.0'=>'1.3_1',
        '1.03'=>'1.03_1',
        '90.0'=>'1.5_1',
        '1.82.'=>'1.82_1',
        '210.0'=>'2.1_1',
        'DEF'=>'_8',
        '1.43'=>'1.43_1',
        '1.87'=>'1.87_1',
        '1.57'=>'1.57_1',
        '1.77'=>'1.77_1',
        '1.27'=>'1.27_1',
        '1.82'=>'1.82_1',
        '91.0'=>'1.4_1',
        'INC1.7'=>'1.7_1',
        '2.6.'=>'2.6_1',
        'INR'=>'_8',
        '2.82'=>'2.82_1',
        '2.61'=>'2.61_1',
        '2.57'=>'2.57_1',
        '2.96'=>'2.96_1',
        '1.39'=>'1.39_1',
        '1.28'=>'1.28_1',
        '2.84'=>'2.84_1',
        '1.14'=>'1.14_1',
        '1.64'=>'1.64_1',
        '2.71'=>'2.71_1',
        '2.02'=>'2.02_1',
        '1.721'=>'1.721_1',
        '2.09'=>'2.09_1',
        '102'=>'1.02_1',
        '.2.9'=>'2.9_1',
        '1.41'=>'1.41_1',
        '1.59'=>'1.59_1',
        '1.94'=>'1.94_1',
        '2.87'=>'2.87_1',
        '86.0'=>'1.9_1',
        '1.80'=>'1.8_1',
        '84.0'=>'2.1_1',
        '75.0'=>'3_1',
        '9.0'=>'5_11',
        '2.80'=>'2.8_1',
        '1.10'=>'1.1_1',
        '2.20'=>'2.2_1',
        '96.0'=>'1_1',
        '0.0'=>'5_11',
        'G'=>'2_1',
        'VG'=>'1.2_1',
        'Drp.'=>'_9',
        '1.62'=>'1.62_1',
        '5.00'=>'5_11',
        'NC'=>'_8',
        'wc'=>'_8',
        'AW'=>'_8',
        '77'=>'2.8_1',
        '88'=>'1.7_1',
        '75'=>'3_1',
        '70'=>'4_11',
        '78'=>'2.7_1',
        '86'=>'1.9_1',
        '81'=>'2.4_1',
        '83'=>'2.2_1',
        '85'=>'2_1',
        'C'=>'2.75_1',
        'B-'=>'2.25_1',
        'B+'=>'1.75_1',
        'UW'=>'_8',
        '87'=>'1.8_1',
        '8.0'=>'5_1',
        '*'=>'_9',
        '89'=>'1.6_1',
        '90'=>'1.5_1',
        'IC'=>'_8',
        'D'=>'_9',
        'Cred'=>'_1',
        '92'=>'1.3_1',
        '93'=>'1.2_1',
        '80'=>'2.5_1',
        '2.93'=>'2.93_1',
        '82'=>'2.3_1',
        '91'=>'1.4_1',
        'W'=>'_12',
        '88.1'=>'1.69_1',
        '95'=>'1_1',
        '94'=>'1.1_1',
        '4.6'=>'4.6_11',
        'NR'=>'_8',
        'B'=>'2_1',
        'F'=>'5_11',
        'Pass'=>'_1',
        'none'=>'_8',
        'IN2'=>'_8',
        '93.7'=>'1.13_1',
        '84.3'=>'2.07_1',
        '92.5'=>'1.25_1',
        '93.0'=>'1.2_1',
        '82.4'=>'2.26_1',
        '83.9'=>'2.11_1',
        'NAS'=>'_8',
        '--'=>'_8',
        'FA'=>'5_11',
        'N.C.'=>'_8',
        '0.00'=>'5_11',
        '79'=>'2.6_1',
        '2.88'=>'2.88_1',
        'AF'=>'5_11',
        'A-'=>'1.5_1',
        'IN1'=>'_8',
        'UA'=>'_8',
        '1.06'=>'1.06_1',
        '1.02'=>'1.02_1',
        '3.50'=>'3.5_1',
        '3.25'=>'3.25_1',
        'Complied'=>'_1',
        'EXT'=>'_8',
        '7.00'=>'5_11',
        '7.0'=>'5_11',
        'A'=>'1.25_1',
        'Comp'=>'_1',
        'INE'=>'_8',
        '3.80'=>'3.8_1',
        '100'=>'1_1',
        'NT'=>'_8',
        'IP'=>'_8',
        'UD'=>'_10',
        'C+'=>'2.5_1',
        'cmpl'=>'_1',
        'WB'=>'_8',
        '4.7'=>'4.7_11',
        '71'=>'4_11',
        '8.00'=>'5_11',
        '3.75'=>'3.75_1',
        '---'=>'_8',
        '3.13'=>'3.13_1',
        'NF'=>'_8',
        'FD'=>'_8',
        '66'=>'5_11',
        '65'=>'5_11',
        '2.77'=>'2.77_1',
        '76'=>'2.9_1',
        'LFR'=>'_8',
        '4.9'=>'4.9_11',
        '97'=>'1_1',
        'HS'=>'_8',
        '85.0'=>'2_1',
        '95.0'=>'1_1',
        '88.0'=>'1.7_1',
        '19'=>'5_1',
        'DRO'=>'_9',
        'N.T.'=>'_8',
        'OD'=>'_8',
        'FDA'=>'_8',
        'IR'=>'_8',
        'N.G.'=>'_8',
        'Grad'=>'_8',
        'NR'=>'_8',
        'Pssd'=>'_1',
        'DNR'=>'_8',
        '1.23'=>'1.23_1',
        '1.01'=>'1.01_1',
        'NYA'=>'_8',
        '1.26'=>'1.26_1',
        '81.0'=>'2.4_1',
        'B+'=>'1.75_1',
        'Fail'=>'5_1',
        'S'=>'1.75_1',
        '-x-'=>'_8',
        'N/G'=>'_8',
        '300h'=>'3_1',
        'FNE'=>'_8',
        '***'=>'_9',
        'PSD.'=>'_1',
        '77.5'=>'2.75_1',
        '82.3'=>'2.27_1',
        '83.3'=>'2.17_1',
        '79.3'=>'2.57_1',
        'CRDT'=>'_1',
        '6.0'=>'5_1',
        '82.2'=>'2.28_1',
        '88.5'=>'1.65_1',
        '2.4/'=>'2.4_1',
        '73'=>'4_1',
        '250'=>'2.5_1',
        '275'=>'2.75_1',
        '225'=>'2.25_1',
        '200'=>'2_1',
        '150'=>'1.5_1',
        '1.09'=>'1.09_1',
        '2/5'=>'2.5_1',
        'C-'=>'3_1',
        'BS'=>'2_1',
        '.1.4'=>'1.4_1',
        '5UD'=>'5_11',
        'N'=>'_8',
        'I.P.'=>'_8',
        'Fair'=>'3_1',
        'IE'=>'_8',
        'O.D.'=>'_9',
        '"W"'=>'_12',
        'L.P'=>'_8',
        '1.38'=>'1.38_1',
        '2.30'=>'2.3_1',
        '99.0'=>'1_1',
        '98.0'=>'1_1',
        '1.84'=>'1.84_1',
        '83.0'=>'2.2_1',
        '80.0'=>'2.5_1',
        '82.0'=>'2.3_1',
        '1.20'=>'1.2_1',
        'INC/2.6'=>'2.6_1',
        '1.90'=>'1.9_1',
        '2.10'=>'2.1_1',
        '1.2.'=>'1.2_1',
        '91.73'=>'1.33_1',
        '94.0'=>'1.1_1',
        '100.0'=>'1_1',
        '83.53'=>'2.15_1',
        '83.75'=>'2.12_1',
        '.1.3'=>'1.3_1',
        '78.0'=>'2.7_1',
        '2.72'=>'2.72_1',
        '4.00'=>'4_11',
        'D+'=>'4_11',
        '.1.2'=>'1.2_1',
        'WF'=>'_10',
        'U.W.'=>'_10',
        '1.70'=>'1.7_1',
        '1.O'=>'1_1',
        '79.0'=>'2.6_1',
        '1.8'=>'1.8_1',
        '1.40'=>'1.4_1',
        '2.70'=>'2.7_1',
        '2.90'=>'2.9_1',
        '3.0'=>'3_1',
        '88.4'=>'1.66_1',
        '92.1'=>'1.29_1',
        '90.26'=>'1.47_1',
        '92.4'=>'1.26_1',
        '93.2'=>'1.18_1',
        '86.7'=>'1.83_1',
        '85.7'=>'1.93_1',
        '85.12'=>'1.99_1',
        '1,7'=>'1.7_1',
        '70.0'=>'4_11',
        '77.0'=>'2.8_1',
        '76.0'=>'2.9_1',
        '7.2'=>'4_11',
        '208.0'=>'2.08_1',
        'InProgres'=>'_8',
        'Completed'=>'_1',
        'Satis'=>'2.5_1',
        'Officially'=>'_8',
        'Satisfacto'=>'2.6_1',
        'SP'=>'2.6_1',
        'GNA'=>'_8',
        '90.3'=>'1.47_1',
        'NAS/1.8'=>'1.8_1',
        '90.00'=>'1.5_1',
        '85.00'=>'2_1',
        '94.00'=>'1.1_1',
        '89.00'=>'1.6_1',
        '96.00'=>'1_1',
        '93.00'=>'1.2_1',
        '95.00'=>'1_1',
        '2,6'=>'2.6_1',
        'enrolled'=>'_8',
        '211.0'=>'2.11_1',
        '2,0'=>'2_1',
        'WP'=>'_10',
        '1,5'=>'1.5_1',
        '30'=>'3_1',
        '28'=>'2.8_1',
        '23'=>'2.3_1',
        '20'=>'2_1',
        '300'=>'3_1',
        '175'=>'1.75_1',
        '(INE)'=>'_8',
        '(DR)'=>'_9',
        '(500)'=>'5_11',
        '(NG)'=>'_8',
        'NoGrade'=>'_8',
        'pased'=>'_1',
        'Drpped'=>'_9',
        '1.08'=>'1.08_1',
        '65.0'=>'5_11',
        'INC3.0'=>'3_1',
        '4.0/5.0'=>'5_11',
        'INC2.75'=>'2.75_1',
        '6.00'=>'5_11',
        '_'=>'_8',
        '84.6'=>'2.04_1',
        '94.9'=>'1.01_1',
        '91.7'=>'1.33_1',
        '87.9'=>'1.71_1',
        '97.2'=>'1_1',
        '88.8'=>'1.62_1',
        '88.7'=>'1.63_1',
        '95.5'=>'1_1',
        '92.9'=>'1.21_1',
        '92.8'=>'1.22_1',
        '86.2'=>'1.88_1',
        '98.3'=>'1_1',
        '97.1'=>'1_1',
        '86.6'=>'1.84_1',
        '98.7'=>'1_1',
        '85.5'=>'1.95_1',
        '84.9'=>'2.01_1',
        '87.6'=>'1.74_1',
        '94.4'=>'1.06_1',
        '96.5'=>'1_1',
        '89.2'=>'1.58_1',
        '97.4'=>'1_1',
        '95.6'=>'1_1',
        '94.8'=>'1.02_1',
        '86.3'=>'1.87_1',
        '90.5'=>'1.45_1',
        '85.4'=>'1.96_1',
        '95.9'=>'1_1',
        '68.3'=>'5_11',
        '85.6'=>'1.94_1',
        '88.2'=>'1.68_1',
        '92.2'=>'1.28_1',
        'INC/3.00'=>'3_1',
        'INC1.75'=>'1.75_1',
        'INC/1.75'=>'1.75_1',
        'NotAttend'=>'_9',
        'IProgress'=>'_8',
        'No.Grade'=>'_8',
        'Dopped'=>'_9',
        'Failed'=>'5_11',
        'GradesNot'=>'_8',
        'DRPP'=>'_9',
        'OnGoing'=>'_8',
        '-DRP'=>'_9',
        'Credited'=>'_1',
        '78.33'=>'2.67_1',
        '88.83'=>'1.62_1',
        '91.4'=>'1.36_1',
        '1,2'=>'1.2_1',
        '1,25'=>'1.25_1',
        '1,75'=>'1.75_1',
        'N.A'=>'_8',
        'Withdrawn'=>'_12',
        '4,5'=>'4.5_11',
        '1,9'=>'1.9_1',
        'On-Going'=>'_8',
        'COND'=>'_8',
        );
        if (isset($grades[$grade])) {
            return $grades[$grade];
        } else {
            return 'NotExists';
        }
    }
    private function retake($grade){
        $retake = array('3.0'=>'3_1',
        '5.0'=>'5_11',
        '2.1'=>'2.1_1',
        '2.6'=>'2.6_1',
        '2.0'=>'2_1',
        '1.2'=>'1.2_1',
        '2.5'=>'2.5_1',
        '2.3'=>'2.3_1',
        '1.4'=>'1.4_1',
        '2.2'=>'2.2_1',
        '1.9'=>'1.9_1',
        '1.8'=>'1.8_1',
        '2.7'=>'2.7_1',
        '2.4'=>'2.4_1',
        '2.9'=>'2.9_1',
        '2.8'=>'2.8_1',
        '1.71'=>'1.71_1',
        '1.6'=>'1.6_1',
        '1.7'=>'1.7_1',
        '1.3'=>'1.3_1',
        '1.5'=>'1.5_1',
        ' 3.0'=>'3_1',
        '.3.0'=>'3_1',
        '2.75'=>'2.75_1',
        '2.5T'=>'2.5_1',
        '2'=>'2_1',
        '3'=>'3_1',
        '1.18'=>'1.18_1',
        '5'=>'5_11',
        '3,0'=>'3_1',
        '4.0'=>'4_11',
        'Passd'=>'_1',
        'Passed'=>'_1',
        'PSD'=>'_1',
        'Pass'=>'_1',
        '1.62'=>'1.62_1',
        '1.75'=>'1.75_1',
        '.5.0'=>'5_11',
        '1.1'=>'1.1_1',
        '2.14'=>'2.14_1',
        '2.45'=>'2.45_1',
        '2.13'=>'2.13_1',
        '3.0T'=>'3_1',
        '2.26'=>'2.26_1',
        '1'=>'1_1',
        'T2.5'=>'2.5_1',
        '2.25'=>'2.25_1',
        '25'=>'2.5_1',
        '1.45'=>'1.45_1',
        '.2.5'=>'2.5_1',
        '.1.7'=>'1.7_1',
        '.2.0'=>'2_1',
        '.2.9'=>'2.9_1',
        '.2.6'=>'2.6_1',
        '1.0'=>'1_1',
        '3.00'=>'3_1',
        '2.10'=>'2.1_1',
        '.2.1'=>'2.1_1',
        '.1.6'=>'1.6_1',
        '.2.7'=>'2.7_1',
        '1.25'=>'1.25_1',
        '.2.4'=>'2.4_1',
        '.2.8'=>'2.8_1',
        '2.00'=>'2_1',
        ' 3.00 '=>'3_1',
        '1.00'=>'1_1',
        '70'=>'4_11',
        '80'=>'2.5_1',
        '78'=>'2.7_1',
        '84'=>'2.1_1',
        '5.00'=>'5_1',
        '1.50'=>'1.5_1',
        'B'=>'2_1',
        '85'=>'2_1',
        'C+'=>'2.5_1',
        'F'=>'5_11',
        'C-'=>'3_1',
        'NG'=>'_8',
        '2.50'=>'2.5_1',
        '89'=>'1.6_1',
        '90'=>'1.5_1',
        '19'=>'1.9_1',
        '2.55'=>'2.55_1',
        '1,7'=>'1.7_1',
        '2.68'=>'2.68_1',
        '2,5'=>'2.5_1',
        'IP'=>'_8',
        'No Grade'=>'_8',
        'In Progres'=>'_8',
        );
        if (isset($retake[$grade])) {
            return $retake[$grade];
        } else {
            return 'NotExists';
        }
    }
}
