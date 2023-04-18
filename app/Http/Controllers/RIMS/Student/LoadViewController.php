<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadViewController extends Controller
{
    public function searchStudent(Request $request){
        $search = $request->input('search');
        $name_services = new NameServices;
        $results = Users::where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->limit(10)
                    ->get();

        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
    public function searchStudents(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $school_year_id = $request->school_year_id;
        $school_year = EducOfferedSchoolYear::where('id',$school_year_id)->first();
        $period = $school_year->grade_period->period;
        if($period=='sum'){
            $program_ids = EducPrograms::pluck('id')->toArray();
        }else{
            $program_ids = EducPrograms::whereHas('program_level', function ($query) use ($period) {
                            $query->where('period', $period);
                        })->pluck('id')->toArray();
        }        
        $results = Users::where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->whereHas('student_info', function ($query) use ($program_ids) {
                        $query->whereIn('program_id', $program_ids);
                    })
                    ->limit(10)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }   
    public function studentTORDiv(Request $request){
        $id = $request->id;
        $program_level = $request->program_level;
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $query = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level)
            ->select('from_school')
            ->groupBy('from_school')
            ->orderBy('year_from')
            ->get()
            ->map(function($query) use ($id,$program_level) {
                $program_ids = StudentsProgram::where('user_id',$id)
                    ->where('from_school',$query->from_school)
                    ->where('program_level_id',$program_level)
                    ->pluck('id')->toArray();
                $year_period = StudentsCourses::whereIn('student_program_id',$program_ids)
                    ->select('year_from','year_to','grade_period_id')
                    ->groupBy('year_from')
                    ->groupBy('grade_period_id')
                    ->orderBy('year_from','ASC')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id){
                        $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                        $courses = StudentsCourses::where('user_id',$id)
                            ->where('grade_period_id',$query->grade_period_id)
                            ->where('year_from',$query->year_from)
                            ->get();
                        return [
                            'grade_period' => $grade_period->name,
                            'period' => $query->year_from.'-'.$query->year_to,
                            'courses' => $courses
                        ];
                    });
                return [
                    'from_school' => $query->from_school,
                    'year_period' => $year_period
                ];
            })->toArray();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentTORDiv',$data);
    } 
    public function studentCurriculumDiv(Request $request){
        $id = $request->id;
        $program_level_id = $request->program_level;
        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level_id)
            ->orderBy('year_from','DESC')
            ->first();
        $query = EducCourses::where('curriculum_id',$student_program->curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($id) {
                
                $courses = EducCourses::where('grade_level_id',$query->grade_level_id);
                                    if($unit_limit->offered_program->school_year->grade_period_id!=4){
                                        $courses = $courses->where('grade_period_id',$unit_limit->offered_program->school_year->grade_period_id);
                                    }
                                    $courses = $courses->where('curriculum_id',$unit_limit->curriculum_id)->get()
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
                                                    $ongoing = StudentsCourses::where('course_id',$course->id)
                                                                ->where('user_id',$student_id)
                                                                ->where('student_course_status_id',NULL)
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
                                                        if($ongoing!=NULL){
                                                            $availability = 2;
                                                            $availability_name = 'Ongoing';
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
        $data = array(
            'id' => $id
        );
        return view('rims/student/studentCurriculumDiv',$data);
    }
}