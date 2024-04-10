<?php

namespace App\Http\Controllers\SIMS\Preenroll;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducTimeMax;
use App\Models\EducYearLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsCoursesAdvise;
use App\Models\StudentsCoursesPreenroll;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadViewController extends Controller
{
    public function preEnrollCourses(Request $request){
        $name_services = new NameServices;
        $user = Auth::user();
        $student_id = $user->id;

        // $time_max = EducTimeMax::first();
        $student_program = StudentsInfo::with('program_level','grade_level')
            ->select('program_id','program_code_id','curriculum_id','specialization_name','program_level_id','grade_level_id')
            ->where('user_id',$student_id)->first();
        $period = $student_program->program_level->period;
        
        $query_school_year = EducOfferedSchoolYear::select('id','grade_period_id')
            ->whereHas('grade_period', function ($query) use ($period) {
                $query->where('period',$period);
            })->orderBy('year_from','DESC')
            ->orderBy('grade_period_id','DESC')
            ->first();

        $program_id = $student_program->program_id;
        $program_code_id = $student_program->program_code_id;
        $curriculum_id = $student_program->curriculum_id;
        $school_year = $query_school_year->id;
        $grade_period_id = $query_school_year->grade_period_id;

        // $courses = EducOfferedCourses::
        //     whereHas('curriculum', function ($query) use ($program_id,$program_code_id,$curriculum_id,$school_year) {
        //         $query->where('curriculum_id',$curriculum_id);
        //         $query->whereHas('offered_program', function ($query) use ($program_id,$program_code_id,$school_year) {
        //             $query->where('program_id',$program_id);
        //             $query->where('program_code_id',$program_code_id);
        //             $query->where('school_year_id',$school_year);
        //         });
        //     })
        //     // ->where('section',$section)            
        //     ->first();
        //$offered_curriculum_id = $courses->offered_curriculum_id;
        $offered_curriculum_id = NULL;
        $year_level = EducYearLevel::select('id')
            ->whereHas('program_level', function ($query) use ($period) {
                $query->where('period',$period);
            })->orderBy('level','ASC')
            ->first();
        $grade_period = EducGradePeriod::select('id')
            ->where('period',$period)
            ->orderBy('id','ASC')
            ->first();
        $year_level_id_selected = $year_level->id;
        $grade_period_id_selected = $grade_period->id;

        $students_courses_id = StudentsCourses::where('user_id',$student_id)
            ->where('course_id','!=',NULL)
            ->where('credit_course_id',NULL)
            ->whereHas('status', function ($query) {
                $query->where('option',1);
            })->orderBy('grade_level_id','ASC')
            ->pluck('course_id')->toArray();
        $students_courses_id_pre = StudentsCourses::where('user_id',$student_id)
            ->where('credit_course_id','!=',NULL)
            ->pluck('credit_course_id')->toArray();
        $students_courses_id_combine = array_merge($students_courses_id, $students_courses_id_pre);
        $query = EducCourses::select('grade_level_id','grade_period_id')
            ->where('curriculum_id',$curriculum_id)
            ->whereNotIn('id',$students_courses_id_combine)
            ->orderBy('grade_level_id','ASC')
            ->orderBy('grade_period_id','ASC')
            ->first();
        if($query!=NULL){
            $year_level_id_selected = $query->grade_level_id;
            $grade_period_id_selected = $query->grade_period_id;
        }
        
        $courses_id_selected = EducCourses::where('curriculum_id',$curriculum_id)
            ->where('grade_level_id',$year_level_id_selected)
            ->where('grade_period_id',$grade_period_id_selected)
            ->where(function ($query) use ($student_program){
                $query->where('specialization_name',$student_program->specialization_name);
                $query->orWhere('specialization_name',NULL);
                $query->orWhere('specialization_name','');
            })
            ->pluck('id')
            ->toArray();        

        $gradeLevels = EducCourses::with('grade_level')
            ->select('grade_level_id')
            ->where('curriculum_id',$curriculum_id)            
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get();
        
        $results =  $gradeLevels->map(function($gradeLevel) use ($student_id,$curriculum_id,$school_year,$student_program,$name_services,$grade_period_id,$offered_curriculum_id) {
                $grade_level_id = $gradeLevel->grade_level_id;
                $grade_periods = EducCourses::with('grade_period')
                    ->where('curriculum_id',$curriculum_id)
                    ->select('grade_period_id')
                    ->where('grade_period_id',$grade_period_id)
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get();

                $grade_period = $grade_periods->map(function($query) use ($student_id,$curriculum_id,$grade_level_id,$school_year,$student_program,$name_services,$offered_curriculum_id) {
                        $grade_period_id = $query->grade_period_id;
                        $coursesList = EducCourses::with('curriculum.programs')
                            ->select('id','curriculum_id','code','name','units','lab','pre_name')
                            ->where('curriculum_id',$curriculum_id)                            
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->where(function ($query) use ($student_program){
                                $query->where('specialization_name',$student_program->specialization_name);
                                $query->orWhere('specialization_name',NULL);
                                $query->orWhere('specialization_name','');
                            })
                            ->get();
                        $courses = $coursesList->map(function($query) use ($student_id,$school_year,$student_program,$name_services,$offered_curriculum_id) {
                                $course_id = $query->id;
                                $availability = 0;
                                $availability_name = 'Available';
                                $instructor = 'TBA';
                                $schedule_implode = 'TBA';
                                $room_implode = 'TBA';
                                $course_conflict = '';
                                $offered_course_id = NULL;
                                $status = '<button class="btn btn-default btn-xs">Required</button>';
                                $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();
                                $check = StudentsCourses::with('status','courses_credit')
                                    ->where('user_id',$student_id)
                                    ->where(function ($query) use ($course_id){
                                        $query->where('course_id',$course_id)
                                        ->orWhereHas('courses_credit', function ($query) use ($course_id) {
                                            $query->where('course_id',$course_id);
                                        });
                                    })
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs">NG</button>';
                                    }else{
                                        if($check->status->option==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$check->status->name.'</button>';
                                            if($check->course_id){
                                                if($check->course_id==$course_id){
                                                    $availability = 2;
                                                    $availability_name = 'Done';
                                                }
                                            }else{
                                                if(count($check->courses_credit)>0){
                                                    foreach($check->courses_credit as $credit){
                                                        if($credit->course_id==$course_id){
                                                            $availability = 4;
                                                            $availability_name = 'Credited';
                                                        }
                                                    }
                                                    $status = '<button class="btn btn-success btn-success-scan btn-xs">Credited</button>';
                                                }
                                            }
                                            
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                
                                $pre_req_ids = EducCoursesPre::where('course_id',$course_id)
                                    ->pluck('pre_id')->toArray();                                                                                               
                                $pre_req = StudentsCourses::select('id')
                                    ->where(function ($query) use ($pre_req_ids){
                                        $query->whereIn('course_id',$pre_req_ids)
                                        ->orWhereHas('courses_credit', function ($query) use ($pre_req_ids) {
                                            $query->where('course_id',$pre_req_ids);
                                        });
                                    })
                                    ->whereIn('student_course_status_id',$passed_statuses)
                                    ->where('user_id',$student_id)
                                    ->get()->count();
                                // $pre_req1 = StudentsCourses::whereIn('credit_course_id',$pre_req_ids)
                                //     ->whereIn('student_course_status_id',$passed_statuses)
                                //     ->where('user_id',$student_id)
                                //     ->get()->count();
                                // $taken = StudentsCourses::select('course_id')
                                //     ->where(function ($query) use ($course_id){
                                //         $query->where('course_id',$course_id)
                                //         ->orWhere('credit_course_id',$course_id);
                                //     })
                                //     ->where('user_id',$student_id)
                                //     ->whereIn('student_course_status_id',$passed_statuses)
                                //     ->orderBy('year_from','DESC')
                                //     ->first();
                                $ongoing = StudentsCourses::select('course_id')
                                    ->where('course_id',$course_id)
                                    ->where('user_id',$student_id)
                                    ->where('student_course_status_id',NULL)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                // $offered_course_ids = EducOfferedCourses::where('course_id','<>',$course_id)
                                //     ->where('offered_curriculum_id',$offered_curriculum_id)
                                //     // ->where('section',$section)
                                //     ->pluck('id')->toArray();
                                if($pre_req!=count($pre_req_ids)){
                                    $availability = 1;
                                    $availability_name = 'Pre Requisite';
                                }
                                if($student_program->curriculum_id!=NULL && $student_program->curriculum_id!=$query->curriculum_id){
                                    $availability = 1;
                                    $availability_name = 'Conflict Curriculum';
                                }
                                if($student_program->program_id!=NULL && $student_program->program_id!=$query->curriculum->programs->id){
                                    $availability = 1;
                                    $availability_name = 'Conflict Program';
                                }
                                // if($taken!=NULL){
                                //     $availability = 2;
                                //     $availability_name = 'Done';
                                // }
                                if($ongoing!=NULL){
                                    $availability = 2;
                                    $availability_name = 'NG';
                                }
                                
                                // $offered_course = EducOfferedCourses::where('course_id', $course_id)
                                //     ->where('offered_curriculum_id',$offered_curriculum_id)
                                //     // ->where('section',$section)
                                //     ->first();
                                // if($offered_course!=NULL){
                                //     $offered_course_id = $offered_course->id;
                                //     $max_student_check = StudentsCourses::where('offered_course_id',$offered_course->id)
                                //         ->get()->count(); 
                                //     if($offered_course->instructor_id!=NULL){
                                //         $instructor = $name_services->firstname($offered_course->instructor->lastname,$offered_course->instructor->firstname,$offered_course->instructor->middlename,$offered_course->instructor->extname);
                                //     }
                                //     if($offered_course->status_id!=1){
                                //         $availability = 1;
                                //         $availability_name = $offered_course->status->name;
                                //     }
                                //     if($max_student_check>$offered_course->max_student){
                                //         $availability = 1;
                                //         $availability_name = 'Full';
                                //     }
                                //     if($taken!=NULL){
                                //         $availability = 2;
                                //         $availability_name = 'Done';
                                //     }
                                //     if($ongoing!=NULL){
                                //         $availability = 2;
                                //         $availability_name = 'NG';
                                //     }
                                //     if(count($offered_course->schedule)>0){
                                //         foreach($offered_course->schedule as $row){
                                //             $days = array();
                                //             if($row->room_id==NULL){
                                //                 $room = 'TBA';
                                //             }else{
                                //                 $room = $row->room->name;
                                //             }
                                //             foreach($row->days as $day){
                                //                 $days[] = $day->day;
                                //             }
                                //             $days1 = implode('',$days);
                                //             $rooms[] = $room;
                                //             $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                //                                 date('h:ia',strtotime($row->time_to)).' '.$days1;
                                //             $course_conflict = $this->course_conflict($offered_course_ids,$row,$days);
                                //         }
                                //         $schedule_implode = implode('<br>',$schedules);
                                //         $room_implode = implode('<br>',$rooms);
                                //     }
                                // }else{
                                //     $availability = 3;
                                //     $availability_name = 'Unvailable';
                                // }

                                $pre_enroll = StudentsCoursesPreenroll::where('user_id',$student_id)
                                    ->where('school_year_id',$school_year)
                                    ->where('course_id',$course_id)
                                    ->first();                                
                                if($pre_enroll!=NULL){
                                    $availability = 5;
                                    $availability_name = 'Pre-enrolled';
                                }

                                $advise = StudentsCoursesAdvise::with('advised_by')
                                    ->select('credit_course_id','updated_by','option','status')
                                    ->where('user_id',$student_id)
                                    ->where('school_year_id',$school_year)
                                    ->whereHas('course', function ($query) use ($course_id) {
                                        $query->where('course_id',$course_id);
                                    })
                                    ->first();
                                $advised = 0;
                                $credit_course_id = '';
                                $advised_by = '';
                                $advised_by_name = '';
                                $advised_status = NULL;
                                
                                if($advise!=NULL){
                                    $advised = 1;
                                    $credit_course_id = $advise->credit_course_id;
                                    $advised_by = $advise->updated_by;
                                    $advised_by_name = $advise->option.'<br>'.$name_services->lastname($advise->advised_by->lastname,$advise->advised_by->firstname,$advise->advised_by->middlename,$advise->advised_by->extname);
                                    $advised_status = $advise->status;
                                }                               

                                return [
                                    'offered_course_id' => $offered_course_id,
                                    'course_id' => $course_id,
                                    'code' => $query->code,
                                    'name' => $query->name,
                                    'units' => $query->units,
                                    'lab' => $query->lab,
                                    'pre_name' => $query->pre_name,
                                    'status' => $status,
                                    'instructor' => $instructor,
                                    'schedule' => $schedule_implode,
                                    'room' => $room_implode,
                                    'availability' => $availability,
                                    'availability_name' => $availability_name,
                                    'course_conflict' => $course_conflict,
                                    'advised' => $advised,
                                    'credit_course_id' => $credit_course_id,
                                    'advised_by' => $advised_by,
                                    'advised_by_name' => $advised_by_name,
                                    'advised_status' => $advised_status
                                ];
                            })->toArray();
                        return [
                            'grade_period' => $query->grade_period->name,
                            'courses' => $courses
                        ];
                    })->toArray();
                return [
                    'year_level' => $gradeLevel->grade_level->name,
                    'year_level1' => $gradeLevel->grade_level->level,
                    'grade_level_id' => $grade_level_id,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        // $program_code = EducOfferedPrograms::where('school_year_id',$school_year)
        //     ->where('program_id',$program_id)->get();
        // $course_add = StudentsCoursesAdvise::where('offered_curriculum_id','<>',$offered_curriculum_id)
        //     ->where('school_year_id',$school_year)
        //     ->where('user_id',$student_id)
        //     ->get()
        //     ->map(function($query) use ($name_services) {
        //         $instructor = 'TBA';
        //         $schedule_implode = 'TBA';
        //         $room_implode = 'TBA';
        //         if($query->course->instructor_id!=NULL){
        //             $instructor = $name_services->firstname($query->course->instructor->lastname,$query->course->instructor->firstname,$query->course->instructor->middlename,$query->course->instructor->extname);
        //         }
        //         if(count($query->course->schedule)>0){
        //             foreach($query->course->schedule as $row){
        //                 $days = array();
        //                 if($row->room_id==NULL){
        //                     $room = 'TBA';
        //                 }else{
        //                     $room = $row->room->name;
        //                 }
        //                 foreach($row->days as $day){
        //                     $days[] = $day->day;
        //                 }
        //                 $days1 = implode('',$days);
        //                 $rooms[] = $room;
        //                 $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
        //                                     date('h:ia',strtotime($row->time_to)).' '.$days1;
        //             }
        //             $schedule_implode = implode('<br>',$schedules);
        //             $room_implode = implode('<br>',$rooms);
        //         }
        //         return [
        //             'id' => $query->offered_course_id,
        //             'program' => $query->course->curriculum->offered_program->name.'-'.$query->course->curriculum->offered_program->program->shorten,
        //             'section' => $query->course->section_code,
        //             'code' => $query->course->code,
        //             'units' => $query->course->course->units,
        //             'schedule' => $schedule_implode,
        //             'room' => $room_implode,
        //             'instructor' => $instructor,
        //             'status' => $query->status,
        //             'advised_by' => $query->updated_by,
        //             'advised_by_name' => $query->option.'<br>'.$name_services->lastname($query->advised_by->lastname,$query->advised_by->firstname,$query->advised_by->middlename,$query->advised_by->extname)
        //         ];
        //     })->toArray();
        $course_add = [];
        $checkStudentPreenroll = StudentsCoursesPreenroll::select('id')
                ->where('user_id',$student_id)
                ->where('school_year_id',$school_year)->first();
        $data = array(
            'query' => $results,
            'student' => $student_program,
            // 'program_code' => $program_code,
            'course_add' => $course_add,
            'type' => 'course',
            'query_school_year' => $query_school_year,
            'offered_curriculum_id' => $offered_curriculum_id,
            'courses_id_selected' => $courses_id_selected,
            // 'time_max' => $time_max,
            'year_level_id_selected' => $year_level_id_selected,
            'grade_period_id_selected' => $grade_period_id_selected,
            'checkStudentPreenroll' => $checkStudentPreenroll
        );
        return view('sims/pre_enroll/preEnrollCourses',$data);
    }
    private function course_conflict($offered_course_ids,$row,$days){
        $course_conflict = '';
        $conflict = EducOfferedSchedule::with('course')->whereIn('offered_course_id',$offered_course_ids)
                                                            ->where('id','<>',$row->id)
                                                            ->where(function ($query) use ($row) {
                                                                $query->where(function ($query) use ($row) {
                                                                    $query->where('time_from','>=',$row->time_from)
                                                                    ->where('time_to','<=',$row->time_from);
                                                                });
                                                                $query->orWhere(function ($query) use ($row) {
                                                                    $query->where('time_from','<=',$row->time_from)
                                                                    ->where('time_to','>',$row->time_from);
                                                                });
                                                                $query->orWhere(function ($query) use ($row) {
                                                                    $query->where('time_from','<',$row->time_to)
                                                                    ->where('time_to','>=',$row->time_to);
                                                                });
                                                                $query->orWhere(function ($query) use ($row) {
                                                                    $query->where('time_from','>=',$row->time_from)
                                                                    ->where('time_to','<=',$row->time_to);
                                                                });
                                                            })
                                                            ->whereHas('days', function ($query) use ($days) {
                                                                $query->whereIn('day', $days);
                                                            })
                                                            ->pluck('offered_course_id')->toArray();
        if(count($conflict)>0){     
            $conflict_codes = EducOfferedCourses::whereIn('id',$conflict)
                    ->pluck('code')->toArray();                                                       
            foreach($conflict_codes as $con){
                $course_con[] = $con;
            }
            $course_conflict = implode(', ',$course_con);
        }
        return $course_conflict;
    }
}