<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducPrograms;
use App\Models\StudentsCourses;
use App\Models\StudentsCoursesAdvise;
use App\Models\StudentsCoursesCredit;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadViewController extends Controller
{
    public function studentInformationDiv(Request $request){
        $id = $request->id;
        $school_year_id = $request->school_year_id;
        $student = StudentsInfo::where('user_id',$id)->first();
        $program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)->pluck('program_id')->toArray();
        $programs = EducPrograms::where('id',$student->program_id)->get();
        $program_codes = EducOfferedPrograms::where('school_year_id',$school_year_id)
                            ->where('program_id',$student->program_id)->get();
        $data = array(
            'student' => $student,
            'programs' => $programs,
            'program_codes' => $program_codes
        );
        return view('rims/enrollment/studentInformationDiv',$data);
    }
    public function programCodeDiv(Request $request){
        $program_id = $request->program_id;
        $school_year_id = $request->school_year_id;
        $program_codes = EducOfferedPrograms::where('school_year_id',$school_year_id)
                            ->where('program_id',$program_id)->get();
        $data = array(
            'program_codes' => $program_codes
        );
        return view('rims/enrollment/programCodeDiv',$data);
    }
    public function programCurriculumDiv(Request $request){
        $student_id = $request->student_id;
        $program_code_id = $request->program_code_id;
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $program_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_code_id)
            ->where('curriculum_id',$student->curriculum_id)->get();
        if($program_curriculum->count()<=0){
            $program_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_code_id)
                ->orderBy('curriculum_id','DESC')->get();
        }
        $data = array(
            'program_curriculum' => $program_curriculum,
            'student' => $student
        );
        return view('rims/enrollment/programCurriculumDiv',$data);
    }
    public function programSectionDiv(Request $request){
        $student_id = $request->student_id;
        $curriculum_id = $request->curriculum_id;
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $program_section = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                            ->select('section')
                            ->groupBy('section')
                            ->orderBy('section')
                            ->get();
        $data = array(
            'program_section' => $program_section,
            'student' => $student
        );
        return view('rims/enrollment/programSectionDiv',$data);
    }
    public function programCoursesDiv(Request $request){
        $student_id = $request->student_id;
        $program_code_id = $request->program_code_id;
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $program_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_code_id)
            ->where('curriculum_id',$student->curriculum_id)->get();
        if($program_curriculum->count()>0){
            return $this->programCoursesDiv1($request);
        }else{
            return $this->studentCurriculumDiv($request);
        }
    }
    private function programCoursesDiv1($request){
        $name_services = new NameServices;
        $student_id = $request->student_id;
        $curriculum_id = $request->curriculum_id;
        $section = $request->section;
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $unit_limit = EducOfferedCurriculum::with('offered_program')->where('id',$curriculum_id)->first();
        $school_year_id = $unit_limit->offered_program->school_year_id;

        $curriculum_ids = EducOfferedCurriculum::where('id',$curriculum_id)
                            ->pluck('curriculum_id')
                            ->toArray();
        $program_courses = EducCourses::whereIn('curriculum_id',$curriculum_ids);
        if($unit_limit->offered_program->school_year->grade_period_id!=4){
            $program_courses = $program_courses->where('grade_period_id',$unit_limit->offered_program->school_year->grade_period_id);
        }
        $program_courses = $program_courses->select('grade_level_id')
                            ->groupBy('grade_level_id')
                            ->orderBy('grade_level_id','ASC')
                            ->get()
                            ->map(function($query) use ($curriculum_id,$section,$student_id,$student,$name_services,$unit_limit) {
                                $courses = EducCourses::where('grade_level_id',$query->grade_level_id);
                                if($unit_limit->offered_program->school_year->grade_period_id!=4){
                                    $courses = $courses->where('grade_period_id',$unit_limit->offered_program->school_year->grade_period_id);
                                }
                                $courses = $courses->where('curriculum_id',$unit_limit->curriculum_id)
                                            ->where(function ($query) use ($student){
                                                $query->where('specialization_name',$student->specialization_name);
                                                $query->orWhere('specialization_name',NULL);
                                                $query->orWhere('specialization_name','');
                                            })
                                            ->get()
                                            ->map(function($course) use ($student,$student_id,$name_services,$curriculum_id,$section) {
                                                $availability = 0;
                                                $availability_name = 'Available';
                                                $instructor = 'TBA';
                                                $schedule_implode = 'TBA';
                                                $room_implode = 'TBA';
                                                $course_conflict = '';
                                                $offered_course_id = NULL;
                                                $course_id = $course->id;
                                                $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();
                                                $pre_req_ids = EducCoursesPre::where('course_id',$course_id)
                                                            ->pluck('pre_id')->toArray();
                                                $pre_req = StudentsCourses::where(function ($query) use ($pre_req_ids){
                                                                $query->whereIn('course_id',$pre_req_ids)
                                                                ->orWhereHas('courses_credit', function ($query) use ($pre_req_ids) {
                                                                    $query->where('course_id',$pre_req_ids);
                                                                });
                                                            })
                                                            ->whereIn('course_id',$pre_req_ids)
                                                            ->where('user_id',$student_id)
                                                            ->whereIn('student_course_status_id',$passed_statuses)
                                                            ->get()->count();
                                                // $pre_req1 = StudentsCourses::whereIn('credit_course_id',$pre_req_ids)
                                                //             ->where('user_id',$student_id)
                                                //             ->whereIn('student_course_status_id',$passed_statuses)
                                                //             ->get()->count();
                                                $taken = StudentsCourses::where(function ($query) use ($course_id){
                                                                $query->where('course_id',$course_id)
                                                                ->orWhereHas('courses_credit', function ($query) use ($course_id) {
                                                                    $query->where('course_id',$course_id);
                                                                });

                                                            })
                                                            ->where('user_id',$student_id)
                                                            ->whereIn('student_course_status_id',$passed_statuses)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $ongoing = StudentsCourses::where('course_id',$course_id)
                                                            ->where('user_id',$student_id)
                                                            ->where('student_course_status_id',NULL)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $offered_course_ids = EducOfferedCourses::where('course_id','<>',$course_id)
                                                            ->where('offered_curriculum_id',$curriculum_id)
                                                            ->where('section',$section)
                                                            ->pluck('id')->toArray();
                                                if($pre_req!=count($pre_req_ids)){
                                                    $availability = 1;
                                                    $availability_name = 'Pre Requisite';
                                                }
                                                if($student->curriculum_id!=NULL && $student->curriculum_id!=$course->curriculum_id){
                                                    $availability = 1;
                                                    $availability_name = 'Conflict Curriculum';
                                                }
                                                if($student->program_id!=NULL && $student->program_id!=$course->curriculum->programs->id){
                                                    $availability = 1;
                                                    $availability_name = 'Conflict Program';
                                                }
                                                $offered_course = EducOfferedCourses::where('course_id', $course->id)
                                                            ->where('offered_curriculum_id',$curriculum_id)
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
                                                return [
                                                    'offered_course_id' => $offered_course_id,
                                                    'course' => $course->name,
                                                    'code' => $course->code,
                                                    'units' => $course->units,
                                                    'pre_name' => $course->pre_name,
                                                    'schedule' => $schedule_implode,
                                                    'room' => $room_implode,
                                                    'instructor' => $instructor,
                                                    'status' => $course->status->name,
                                                    'availability' => $availability,
                                                    'availability_name' => $availability_name,
                                                    'course_conflict' => $course_conflict
                                                ];
                                            })->toArray();
                                return [
                                    'year_level' => $query->grade_level->name,
                                    'year_level1' => $query->grade_level->level,
                                    'unit_limit' => $unit_limit->offered_program->school_year->unit_limit,
                                    'courses' => $courses
                                ];
                            })->toArray();
        $advised = StudentsCoursesAdvise::where('school_year_id',$school_year_id)
            ->where('user_id',$student_id)
            ->get()
            ->map(function($course) use ($name_services) {
                $instructor = 'TBA';
                $schedule_implode = 'TBA';
                $room_implode = 'TBA';
                $advised_by = $name_services->firstname($course->advised_by->lastname,
                    $course->advised_by->firstname,
                    $course->advised_by->middlename,
                    $course->advised_by->extname);
                if($course->course->instructor_id!=NULL){
                    $instructor = $name_services->firstname($course->course->instructor->lastname,
                        $course->course->instructor->firstname,
                        $course->course->instructor->middlename,
                        $course->course->instructor->extname);
                }
                if(count($course->course->schedule)>0){
                    foreach($course->course->schedule as $row){
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
                if($course->credit_course_id==NULL){
                    $credit_course_id = $course->course->course_id;
                }else{
                    $credit_course_id = $course->credit_course_id;
                }
                return [
                    'id' => $course->id,
                    'program' => $course->course->curriculum->offered_program->name.'-'.$course->course->curriculum->offered_program->program->shorten,
                    'section_code' => $course->course->section_code,
                    'course_code' => $course->course->code,
                    'units' => $course->course->course->units,
                    'schedule' => $schedule_implode,
                    'room' => $room_implode,
                    'instructor' => $instructor,
                    'advised_by' => $advised_by,
                    'date_time' => date('M d, Y h:i: a',strtotime($course->created_at)),
                    'status' => $course->status,
                    'credit_course_id' => $credit_course_id
                ];
            })->toArray();
        $data = array(
            'program_courses' => $program_courses,
            'type' => 'enroll',
            // 'type' => 'course',
            'student' => $student,
            'advised' => $advised
        );
        return view('rims/enrollment/programCoursesDiv',$data);
    }

    private function studentCurriculumDiv($request){
        $system_selected = $request->session()->get('system_selected');
        $id = $request->student_id;
        $program_code_id = $request->program_code_id;
        $offered_curriculum_id = $request->curriculum_id;
        $program_offered = EducOfferedPrograms::with('school_year','program')
            ->where('id',$program_code_id)
            ->first();
        $grade_period_id = $program_offered->school_year->grade_period_id;
        $program_level_id = $program_offered->program->program_level_id;
        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level_id)
            ->select('curriculum_id','specialization_name')
            ->orderBy('year_from','DESC')
            ->orderBy('id','DESC')
            ->first();
        $curriculum_id = $student_program->curriculum_id;
        $specialization_name = $student_program->specialization_name;
        $specialization = EducCourses::where('curriculum_id',$curriculum_id)
            ->where('specialization_name','!=','')
            ->select('specialization_name')
            ->groupBy('specialization_name')
            ->get();
        $query = EducCourses::with('grade_level')
            ->where('curriculum_id',$curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($id,$curriculum_id,$student_program,$grade_period_id) {
                $grade_level_id = $query->grade_level_id;
                $grade_period = EducCourses::with('grade_period')
                    ->where('curriculum_id',$curriculum_id)
                    ->where('grade_period_id',$grade_period_id)
                    ->select('grade_period_id')
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$student_program,$grade_period_id) {
                        $courses = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->where(function ($query) use ($student_program){
                                $query->where('specialization_name',$student_program->specialization_name);
                                $query->orWhere('specialization_name',NULL);
                                $query->orWhere('specialization_name','');
                            })
                            ->select('id','code','name','units','lab','pre_name')
                            ->get()
                            ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$grade_period_id) {
                                $status = '<button class="btn btn-default btn-xs" style="font-size:10px">Required</button>';
                                $student_course_status = NULL;
                                $course_id = $query->id;
                                $check = StudentsCourses::where('user_id',$id)
                                    ->where(function ($query) use ($course_id){
                                        $query->where('course_id',$course_id)
                                        ->orWhereHas('courses_credit', function ($query) use ($course_id) {
                                            $query->where('course_id',$course_id);
                                        });
                                    })
                                    ->select('student_course_status_id')
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs" style="font-size:10px">NG</button>';
                                    }else{
                                        $student_course_status = $check->student_course_status_id;
                                        if($check->status->option==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                $course_other = StudentsCoursesCredit::with('student_course')
                                    ->where('user_id',$id)
                                    ->where('course_id',$course_id)
                                    ->first();
                                return [
                                    'id' => $query->id,
                                    'code' => $query->code,
                                    'name' => $query->name,
                                    'units' => $query->units,
                                    'lab' => $query->lab,
                                    'pre_name' => $query->pre_name,
                                    'status' => $status,
                                    'student_course_status' => $student_course_status,
                                    'course_other' => $course_other
                                ];
                            })->toArray();
                        return [
                            'grade_period' => $query->grade_period->name,
                            'courses' => $courses
                        ];
                    })->toArray();
                return [
                    'year_level' => $query->grade_level->name,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        $courses_id = EducCourses::where('curriculum_id',$curriculum_id)
            ->pluck('id')
            ->toArray();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $course_other = StudentsCourses::where('user_id',$id)
            ->where('year_from','>',1)
            ->select('year_from','year_to','grade_period_id','program_shorten','school_name')
            ->groupBy('year_from')
            ->groupBy('grade_period_id')
            ->orderBy('year_from','ASC')
            ->orderBy('grade_period_id','ASC')
            ->get()
            ->map(function($query) use ($id,$courses_id){
                $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                $courses = StudentsCourses::where('user_id',$id)
                    ->where('grade_period_id',$query->grade_period_id)
                    ->where('year_from',$query->year_from)
                    ->where(function ($query) use ($courses_id){
                        $query->where('course_id',NULL)
                        ->orWhereNotIn('course_id',$courses_id);
                    })
                    ->get();
                return [
                    'school_name' => $query->school_name,
                    'grade_period' => $grade_period->name,
                    'period' => $query->year_from.'-'.$query->year_to,
                    'courses' => $courses,
                    'program_shorten' => $query->program_shorten
                ];
            })->toArray();
        $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();

        $data = array(
            'id' => $id,
            'query' => $query,
            'course_other' => $course_other,
            'passed_statuses' => $passed_statuses,
            'specialization' => $specialization,
            'system_selected' => $system_selected,
            'specialization_name' => $specialization_name
        );
        return view('rims/enrollment/programCoursesDiv1',$data);
    }
    public function programAddSelect(Request $request){
        $result = 'Unavailable';
        $program_id = $request->program_id;
        $curriculum_id_selected = $request->curriculum_id_selected;
        $curriculum_id = '';
        $curriculums = '';
        $section = '';
        if($program_id!=''){
            $student_id = $request->student_id;
            $student = StudentsInfo::where('user_id',$student_id)->first();
            $offered_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_id)
                        ->where('curriculum_id','<>',$student->curriculum_id)
                        ->where('id','<>',$curriculum_id_selected);
            $curriculum = $offered_curriculum->orderBy('curriculum_id','ASC')
                        ->first();
            if($curriculum!=NULL){
                $curriculums = $offered_curriculum->orderBy('curriculum_id','ASC')
                            ->get()
                            ->map(function($query) {
                                return [
                                    'id' => $query->id,
                                    'name' => $query->curriculum->year_from.'-'.$query->curriculum->year_to.' ('.$query->code.')'
                                ];
                            })->toArray();
                $curriculum_id = $curriculum->id;
                $section = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                                ->select('section')
                                ->groupBy('section')
                                ->orderBy('section')
                                ->get()
                                ->map(function($query) {
                                    return [
                                        'section' => $query->section
                                    ];
                                })->toArray();
                if(count($section)>0){
                    $result = 'success';
                }
            }
        }else{
            $result = 'blank';
        }
        $response = array('result' => $result,
                          'curriculum_id' => $curriculum_id,
                          'curriculum' => $curriculums,
                          'section' => $section
                        );
        return response()->json($response);
    }
    public function programAddCourseDiv(Request $request){
        $name_services = new NameServices;
        $student_id = $request->student_id;
        $curriculum_id_selected = $request->curriculum_id_selected;
        $section_selected = $request->section_selected;
        $curriculum_id = $request->curriculum_id;
        $section = $request->section;
        $courses_sel = $request->courses;
        if($courses_sel==''){
            $courses_sel = [];
        }
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $unit_limit = EducOfferedCurriculum::where('id',$curriculum_id)->first();
        $program_courses = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                            ->select('year_level')
                            ->groupBy('year_level')
                            ->orderBy('year_level','ASC')
                            ->get()
                            ->map(function($query)
                                    use ($name_services,
                                        $curriculum_id,
                                        $student_id,
                                        $section,
                                        $unit_limit,$curriculum_id_selected,
                                        $section_selected,
                                        $courses_sel) {
                                $courses = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                                            ->where('year_level',$query->year_level)
                                            ->where('section',$section)
                                            ->whereNotIn('id',$courses_sel)
                                            ->get()
                                            ->map(function($course)
                                                    use ($name_services,
                                                        $curriculum_id,
                                                        $student_id,
                                                        $section,
                                                        $curriculum_id_selected,
                                                        $section_selected) {
                                                $availability = 0;
                                                $availability_name = 'Available';
                                                $instructor = 'TBA';
                                                $schedule_implode = 'TBA';
                                                $room_implode = 'TBA';
                                                $course_conflict = '';
                                                $offered_course_id = NULL;
                                                $course_id = $course->id;
                                                $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();
                                                $max_student_check = StudentsCourses::where('offered_course_id',$course_id)
                                                            ->get()->count();
                                                $pre_req_ids = EducCoursesPre::where('course_id',$course_id)
                                                            ->pluck('pre_id')->toArray();
                                                $pre_req = StudentsCourses::where(function ($query) use ($pre_req_ids){
                                                                $query->whereIn('course_id',$pre_req_ids)
                                                                ->orWhereHas('courses_credit', function ($query) use ($pre_req_ids) {
                                                                    $query->where('course_id',$pre_req_ids);
                                                                });
                                                            })
                                                            ->where('user_id',$student_id)
                                                            ->whereIn('student_course_status_id',$passed_statuses)
                                                            ->get()->count();
                                                // $pre_req1 = StudentsCourses::whereIn('credit_course_id',$pre_req_ids)
                                                //             ->where('user_id',$student_id)
                                                //             ->whereIn('student_course_status_id',$passed_statuses)
                                                //             ->get()->count();
                                                $taken = StudentsCourses::where(function ($query) use ($course_id){
                                                                $query->where('course_id',$course_id)
                                                                ->orWhereHas('courses_credit', function ($query) use ($course_id) {
                                                                    $query->where('course_id',$course_id);
                                                                });
                                                            })
                                                            ->where('user_id',$student_id)
                                                            ->whereIn('student_course_status_id',$passed_statuses)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $ongoing = StudentsCourses::where('course_id',$course_id)
                                                            ->where('user_id',$student_id)
                                                            ->where('student_course_status_id',NULL)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $offered_course_ids1 = EducOfferedCourses::where('course_id','<>',$course_id)
                                                            ->where('offered_curriculum_id',$curriculum_id)
                                                            ->where('section',$section)
                                                            ->pluck('id')->toArray();
                                                $offered_course_ids2 = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id_selected)
                                                            ->where('section',$section_selected)
                                                            ->pluck('id')->toArray();
                                                $offered_course_ids = array_merge($offered_course_ids1,$offered_course_ids2);
                                                // if(count($pre_req_ids)>0){
                                                //     $availability = 1;
                                                //     $availability_name = 'Pre Requisite';
                                                // }
                                                if($pre_req!=count($pre_req_ids)){
                                                    $availability = 1;
                                                    $availability_name = 'Pre Requisite';
                                                }
                                                $offered_course_id = $course->id;
                                                if($course->instructor_id!=NULL){
                                                    $instructor = $name_services->firstname($course->instructor->lastname,$course->instructor->firstname,$course->instructor->middlename,$course->instructor->extname);
                                                }
                                                if($course->status_id!=1){
                                                    $availability = 1;
                                                    $availability_name = $course->status->name;
                                                }
                                                if($max_student_check>$course->max_student){
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
                                                if(count($course->schedule)>0){
                                                    foreach($course->schedule as $row){
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
                                                return [
                                                    'offered_course_id' => $offered_course_id,
                                                    'course' => $course->course->name,
                                                    'code' => $course->course->code,
                                                    'units' => $course->course->units,
                                                    'pre_name' => $course->course->pre_name,
                                                    'schedule' => $schedule_implode,
                                                    'room' => $room_implode,
                                                    'instructor' => $instructor,
                                                    'status' => $course->status->name,
                                                    'availability' => $availability,
                                                    'availability_name' => $availability_name,
                                                    'course_conflict' => $course_conflict
                                                ];
                                            });
                                return [
                                    'year_level' => $query->course->grade_level->name,
                                    'year_level1' => $query->course->grade_level->level,
                                    'unit_limit' => $unit_limit->offered_program->school_year->unit_limit,
                                    'courses' => $courses
                                ];
                            })->toArray();
        $data = array(
            'program_courses' => $program_courses,
            'type' => 'add',
            'student' => $student
        );
        return view('rims/enrollment/programCoursesDiv',$data);
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
    public function dateList(Request $request){
        $school_year_id = $request->school_year_id;
        $result = 'error';
        if($school_year_id!=NULL){
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $dates = StudentsCourses::
                where('school_year_id', $school_year_id)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('created_at','DESC')
                ->orderBy('id','DESC')->get()
                ->map(function($query) {
                    return [
                        'id' => date('Y-m-d',strtotime($query->created_at)),
                        'text' => date('F d, Y',strtotime($query->created_at))
                    ];
                })->toArray();
            if($dates){
                $result = 'success';
            }
        }
        $response = array('result' => $result,
                          'dates' => $dates);
        return response()->json($response);
    }
}
