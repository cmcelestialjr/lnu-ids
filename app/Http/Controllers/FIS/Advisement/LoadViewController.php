<?php

namespace App\Http\Controllers\FIS\Advisement;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use App\Models\StudentsCoursesAdvise;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadViewController extends Controller
{
    public function studentInfo(Request $request){
        $school_year = $request->school_year;
        $student_id = $request->student_id;
        $result = 'error';
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $program = '';
        $level = '';        
        if($student!=NULL){
            $program = $student->program->name.' ('.$student->program->shorten.')';
            $level = $student->grade_level->name;
            $code = EducOfferedPrograms::where('school_year_id',$school_year)
                ->where('program_id',$student->program_id)
                ->get()
                ->map(function($query) {
                    return [
                        'id' => $query->id,
                        'name' => $query->name
                    ];
                })->toArray();
            $result = 'success';
        }
        $response = array('result' => $result,
                          'program' => $program,
                          'level' => $level,
                          'code' => $code
                        );
        return response()->json($response);
    }
    public function curriculumSelect(Request $request){
        $code = $request->code;
        $result = 'error';
        $curriculum = EducOfferedCurriculum::where('offered_program_id',$code)
                ->get()
                ->map(function($query) {
                    return [
                        'id' => $query->id,
                        'name' => $query->curriculum->year_from.'-'.$query->curriculum->year_to.' ('.$query->code.')'
                    ];
                })->toArray();
        if(count($curriculum)>0){
            $result = 'success';
        }
        $response = array('result' => $result,
                          'curriculum' => $curriculum
                        );
        return response()->json($response);
    }
    public function sectionSelect(Request $request){
        $curriculum = $request->curriculum;
        $result = 'error';
        $section = EducOfferedCourses::where('offered_curriculum_id',$curriculum)
                ->select('section')
                ->groupBy('section')
                ->get()
                ->map(function($query) {
                    return [
                        'id' => $query->section,
                        'name' => $query->section
                    ];
                })->toArray();
        if(count($section)>0){
            $result = 'success';
        }
        $response = array('result' => $result,
                          'section' => $section
                        );
        return response()->json($response);
    }
    public function studentAdvisement(Request $request){
        $name_services = new NameServices;
        $user = Auth::user();
        $instructor_id = $user->id;        
        $school_year = $request->school_year;
        $student_id = $request->student_id;
        $code = $request->code;
        $offered_curriculum_id = $request->curriculum;
        $section = $request->section;
        $student_program = StudentsInfo::where('user_id',$student_id)->first();
        $query_school_year = EducOfferedSchoolYear::where('id',$school_year)->first();
        $curriculum_id = $student_program->curriculum_id;
        $program_id = $student_program->program_id;        
        $query = EducCourses::where('curriculum_id',$curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($student_id,$curriculum_id,$school_year,$offered_curriculum_id,$section,$student_program,$name_services) {
                $grade_level_id = $query->grade_level_id;
                $grade_period = EducCourses::where('curriculum_id',$curriculum_id)
                    ->select('grade_period_id')
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($student_id,$curriculum_id,$grade_level_id,$school_year,$offered_curriculum_id,$section,$student_program,$name_services) {
                        $grade_period_id = $query->grade_period_id;
                        $courses = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->get()
                            ->map(function($query) use ($student_id,$school_year,$offered_curriculum_id,$section,$student_program,$name_services) {
                                $course_id = $query->id;
                                $status = '<button class="btn btn-default btn-xs">Untaken</button>';
                                $check = StudentsCourses::where('user_id',$student_id)
                                    ->where('course_id',$course_id)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs">NG</button>';
                                    }else{
                                        if($check->student_course_status_id==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$check->status->name.'</button>';
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                $availability = 0;
                                $availability_name = 'Available';
                                $instructor = 'TBA';
                                $schedule_implode = 'TBA';
                                $room_implode = 'TBA';
                                $course_conflict = '';
                                $offered_course_id = NULL;
                                $pre_req_ids = EducCoursesPre::where('course_id',$course_id)
                                    ->pluck('pre_id')->toArray();                                                                                               
                                $pre_req = StudentsCourses::whereIn('course_id',$pre_req_ids)
                                    ->where('user_id',$student_id)
                                    ->get()->count();
                                $pre_req1 = StudentsCourses::whereIn('credit_course_id',$pre_req_ids)
                                    ->where('user_id',$student_id)
                                    ->get()->count();
                                $taken = StudentsCourses::where('course_id',$course_id)
                                    ->where('user_id',$student_id)
                                    ->where('student_course_status_id',1)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                $ongoing = StudentsCourses::where('course_id',$course_id)
                                    ->where('user_id',$student_id)
                                    ->where('student_course_status_id',NULL)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                $offered_course_ids = EducOfferedCourses::where('course_id','<>',$course_id)
                                    ->where('offered_curriculum_id',$offered_curriculum_id)
                                    ->where('section',$section)
                                    ->pluck('id')->toArray();
                                if(($pre_req+$pre_req1)!=count($pre_req_ids)){
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
                                $offered_course = EducOfferedCourses::where('course_id', $course_id)
                                    ->where('offered_curriculum_id',$offered_curriculum_id)
                                    ->where('section',$section)->first();
                                if($offered_course!=NULL){
                                    $offered_course_id = $offered_course->id;
                                    $max_student_check = StudentsCourses::where('offered_course_id',$offered_course->id)
                                        ->get()->count(); 
                                    if($offered_course->instructor_id!=NULL){
                                        $instructor = $name_services->firstname($offered_course->instructor->lastname,$offered_course->instructor->firstname,$offered_course->instructor->middlename,$offered_course->instructor->extname);
                                    }
                                    if($offered_course->status_id!=1){
                                        $availability = 1;
                                        $availability_name = $offered_course->status->name;
                                    }
                                    if($max_student_check>$offered_course->max_student){
                                        $availability = 1;
                                        $availability_name = 'Full';
                                    }
                                    if($taken!=NULL){
                                        $availability = 2;
                                        $availability_name = 'Done';
                                    }
                                    if($ongoing!=NULL){
                                        $availability = 2;
                                        $availability_name = 'NG';
                                    }
                                    if(count($offered_course->schedule)>0){
                                        foreach($offered_course->schedule as $row){
                                            $days = array();
                                            if($row->room_id==NULL){
                                                $room = 'TBA';
                                            }else{
                                                $room = $row->room->name;
                                            }
                                            foreach($row->days as $day){
                                                $days[] = $day->day;
                                            }
                                            $days1 = implode('',$days);
                                            $rooms[] = $room;
                                            $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                                                date('h:ia',strtotime($row->time_to)).' '.$days1;
                                            $course_conflict = $this->course_conflict($offered_course_ids,$row,$days);
                                        }
                                        $schedule_implode = implode('<br>',$schedules);
                                        $room_implode = implode('<br>',$rooms);
                                    }
                                }else{
                                    $availability = 3;
                                    $availability_name = 'Unvailable';
                                }
                                $advise = StudentsCoursesAdvise::where('user_id',$student_id)
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
                                    $advised_by_name = $name_services->lastname($advise->advised_by->lastname,$advise->advised_by->firstname,$advise->advised_by->middlename,$advise->advised_by->extname);
                                    $advised_status = $advise->status;
                                }
                                return [
                                    'offered_course_id' => $offered_course_id,
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
                    'year_level' => $query->grade_level->name,
                    'year_level1' => $query->grade_level->level,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        $program_code = EducOfferedPrograms::where('school_year_id',$school_year)
            ->where('program_id',$program_id)->get();
        $course_add = StudentsCoursesAdvise::where('offered_curriculum_id','<>',$offered_curriculum_id)
            ->where('school_year_id',$school_year)
            ->where('user_id',$student_id)
            ->get()
            ->map(function($query) use ($name_services) {
                $instructor = 'TBA';
                $schedule_implode = 'TBA';
                $room_implode = 'TBA';
                if($query->course->instructor_id!=NULL){
                    $instructor = $name_services->firstname($query->course->instructor->lastname,$query->course->instructor->firstname,$query->course->instructor->middlename,$query->course->instructor->extname);
                }
                if(count($query->course->schedule)>0){
                    foreach($query->course->schedule as $row){
                        $days = array();
                        if($row->room_id==NULL){
                            $room = 'TBA';
                        }else{
                            $room = $row->room->name;
                        }
                        foreach($row->days as $day){
                            $days[] = $day->day;
                        }
                        $days1 = implode('',$days);
                        $rooms[] = $room;
                        $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                            date('h:ia',strtotime($row->time_to)).' '.$days1;
                    }
                    $schedule_implode = implode('<br>',$schedules);
                    $room_implode = implode('<br>',$rooms);
                }
                return [
                    'id' => $query->offered_course_id,
                    'program' => $query->course->curriculum->offered_program->name.'-'.$query->course->curriculum->offered_program->program->shorten,
                    'section' => $query->course->section_code,
                    'code' => $query->course->code,
                    'units' => $query->course->course->units,
                    'schedule' => $schedule_implode,
                    'room' => $room_implode,
                    'instructor' => $instructor,
                    'status' => $query->status,
                    'advised_by' => $query->updated_by,
                    'advised_by_name' => $name_services->lastname($query->advised_by->lastname,$query->advised_by->firstname,$query->advised_by->middlename,$query->advised_by->extname)
                ];
            })->toArray();
        $data = array(
            'query' => $query,
            'student' => $student_program,
            'program_code' => $program_code,
            'course_add' => $course_add,
            'type' => 'course',
            'query_school_year' => $query_school_year,
            'instructor_id' => $instructor_id
        );
        return view('fis/advisement/studentAdvisement',$data);
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