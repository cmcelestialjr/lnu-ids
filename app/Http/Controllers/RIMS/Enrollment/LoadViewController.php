<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducPrograms;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;

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
        $program_curriculum = EducOfferedCurriculum::where('offered_program_id',$program_code_id)->get();
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
        $name_services = new NameServices;
        $student_id = $request->student_id;
        $curriculum_id = $request->curriculum_id;
        $section = $request->section;
        $student = StudentsInfo::where('user_id',$student_id)->first();
        $unit_limit = EducOfferedCurriculum::where('id',$curriculum_id)->first();
        $curriculum_ids = EducOfferedCurriculum::where('id',$curriculum_id)
                            ->pluck('curriculum_id')
                            ->toArray();
        $program_courses = EducCourses::whereIn('curriculum_id',$curriculum_ids)
                            ->where('grade_period_id',$unit_limit->offered_program->school_year->grade_period_id)
                            ->select('grade_level_id')
                            ->groupBy('grade_level_id')
                            ->orderBy('grade_level_id','ASC')
                            ->get()
                            ->map(function($query) use ($curriculum_id,$section,$student_id,$student,$name_services,$unit_limit) {
                                $courses = EducCourses::where('grade_level_id',$query->grade_level_id)
                                            ->where('grade_period_id',$unit_limit->offered_program->school_year->grade_period_id)
                                            ->where('curriculum_id',$unit_limit->curriculum_id)->get()
                                            ->map(function($course) use ($student,$student_id,$name_services,$curriculum_id,$section) {
                                                $availability = 0;
                                                $availability_name = 'Available';
                                                $instructor = 'TBA';
                                                $schedule_implode = 'TBA';
                                                $room_implode = 'TBA';
                                                $course_conflict = '';
                                                $offered_course_id = NULL;
                                                $pre_req_ids = EducCoursesPre::where('course_id',$course->id)
                                                            ->pluck('pre_id')->toArray();                                                                                               
                                                $pre_req = StudentsCourses::whereIn('course_id',$pre_req_ids)
                                                            ->where('user_id',$student_id)
                                                            ->get()->count();
                                                $pre_req1 = StudentsCourses::whereIn('credit_course_id',$pre_req_ids)
                                                            ->where('user_id',$student_id)
                                                            ->get()->count();
                                                $taken = StudentsCourses::where('course_id',$course->id)
                                                            ->where('user_id',$student_id)
                                                            ->where('student_course_status_id',1)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $offered_course_ids = EducOfferedCourses::where('course_id','<>',$course->id)
                                                            ->where('offered_curriculum_id',$curriculum_id)
                                                            ->where('section',$section)
                                                            ->pluck('id')->toArray();
                                                if(($pre_req+$pre_req1)!=count($pre_req_ids)){
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
        $data = array(
            'program_courses' => $program_courses,
            'type' => 'course'
        );
        return view('rims/enrollment/programCoursesDiv',$data);
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
        $unit_limit = EducOfferedCurriculum::where('id',$curriculum_id)->first();
        $program_courses = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                            ->select('year_level')
                            ->groupBy('year_level')
                            ->orderBy('year_level','ASC')
                            ->get()
                            ->map(function($query) 
                                    use ($name_services,$curriculum_id,$student_id,$section,
                                        $unit_limit,$curriculum_id_selected,$section_selected) {
                                $courses = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                                            ->where('year_level',$query->year_level)
                                            ->where('section',$section)
                                            ->get()
                                            ->map(function($course) 
                                                    use ($name_services,$curriculum_id,$student_id,$section,
                                                        $curriculum_id_selected,$section_selected) {
                                                $availability = 0;
                                                $availability_name = 'Available';
                                                $instructor = 'TBA';
                                                $schedule_implode = 'TBA';
                                                $room_implode = 'TBA';
                                                $course_conflict = '';
                                                $offered_course_id = NULL;
                                                $max_student_check = StudentsCourses::where('offered_course_id',$course->id)
                                                            ->get()->count();
                                                $pre_req_ids = EducCoursesPre::where('course_id',$course->id)
                                                            ->pluck('pre_id')->toArray();
                                                $taken = StudentsCourses::where('course_id',$course->id)
                                                            ->where('user_id',$student_id)
                                                            ->where('student_course_status_id',1)
                                                            ->orderBy('year_from','DESC')
                                                            ->first();
                                                $offered_course_ids1 = EducOfferedCourses::where('course_id','<>',$course->id)
                                                            ->where('offered_curriculum_id',$curriculum_id)
                                                            ->where('section',$section)
                                                            ->pluck('id')->toArray();
                                                $offered_course_ids2 = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id_selected)
                                                            ->where('section',$section_selected)
                                                            ->pluck('id')->toArray();
                                                $offered_course_ids = array_merge($offered_course_ids1,$offered_course_ids2);
                                                if(count($pre_req_ids)>0){
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
            'type' => 'add'
        );
        return view('rims/enrollment/programCoursesDiv',$data);
    }
}