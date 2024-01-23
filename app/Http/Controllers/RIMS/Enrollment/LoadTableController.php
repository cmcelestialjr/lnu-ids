<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadTableController extends Controller
{  
    public function enrollmentTable(Request $request){
        if($request->by=='program'){
            return $this->enrollmentTableProgram($request);
        }else{
            return $this->enrollmentTableDate($request);
        }
    }
    private function enrollmentTableProgram(Request $request){
        $data = array();
        $id = $request->id;
        $query = EducOfferedPrograms::where('school_year_id',$id)
                    ->orderBy('program_id')
                    ->orderBy('name')->get()
                    ->map(function($query) {
                        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$query->id)
                                    ->pluck('id')->toArray();
                        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                    ->pluck('id')->toArray();
                        $student_ids = StudentsCourses::whereIn('offered_course_id',$offered_course_ids)
                                    ->pluck('user_id')->toArray();
                        $students = Users::whereIn('id',$student_ids)
                                    ->pluck('id')->toArray();
                        $count = count($students);
                        return [
                            'id' => $query->id,
                            'department' => $query->department->shorten,
                            'program' => $query->program->shorten,
                            'code' => $query->name,
                            'count' => $count
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['department'];
                $data_list['f3'] = $r['program'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['count'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan enrollmentViewModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function enrollmentTableDate(Request $request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $date = $request->date;
        if($data!=''){
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $query = StudentsCourses::where('school_year_id',$id)
                        ->whereDate('created_at',$date)
                        ->groupBy('user_id')
                        ->orderBy('created_at','DESC')->get()
                        ->map(function($query) use ($name_services) {
                            $name = $name_services->lastname($query->student_info->info->lastname,$query->student_info->info->firstname,$query->student_info->info->middlename,$query->student_info->info->extname);

                            return [
                                'id' => $query->user_id,
                                'id_no' => $query->student_info->id_no,
                                'name' => $name,
                                'department' => $query->student_info->program->departments->shorten,
                                'program' => $query->student_info->program->shorten,
                                'level' => $query->student_info->grade_level->name,
                                'status' => $query->student_info->status->name,
                                'student_program_id' => $query->student_program_id,
                                'school_year' => $query->school_year->year_from.'-'.$query->school_year->year_to,
                                'school_period' => str_replace(' ','-',$query->school_year->grade_period->name)
                            ];
                        })->toArray();
            if(count($query)>0){
                $x = 1;
                foreach($query as $r){
                    $data_list['f1'] = $x;
                    $data_list['f2'] = $r['id_no'];
                    $data_list['f3'] = $r['name'];
                    $data_list['f4'] = $r['department'];
                    $data_list['f5'] = $r['program'];
                    $data_list['f6'] = $r['level'];
                    $data_list['f7'] = $r['status'];
                    $data_list['f8'] = '<button class="btn btn-info btn-info-scan btn-xs coursesViewModal"
                                            data-id="'.$r['id'].'"
                                            data-spid="'.$r['student_program_id'].'"><span class="fa fa-eye"></span></button>';
                    $data_list['f9'] = '<a class="btn btn-primary btn-primary-scan btn-xs view"
                                            href="/enrollment_form/'.$r['id_no'].'/'.$r['school_year'].'/'.$r['school_period'].'" target="_blank">
                                            <span class="fa fa-file"></span>
                                        </a>';
                    array_push($data,$data_list);
                    $x++;
                }
            }
        }
        return  response()->json($data);
    }
    public function courseAnotherTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $course = EducOfferedCourses::where('id',$id)->first();
        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $offered_course_ids_not = EducOfferedCourses::where('offered_curriculum_id',$course->offered_curriculum_id)
                                ->where('section',$course->section)
                                ->pluck('id')->toArray();
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)
                                ->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$offered_program_ids)                                
                                ->pluck('id')->toArray();
        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                ->whereNotIn('id',$offered_course_ids_not)
                                ->where('year_level',$course->year_level)
                                ->where('status_id',1)
                                ->whereHas('course', function ($query) use ($course) {
                                    $query->where('name','LIKE','%'.$course->course->name.'%');
                                })
                                ->pluck('id')->toArray();
        $offered_course_idss = array();
        $offered_course_ids_list = EducOfferedCourses::where('offered_curriculum_id',$course->offered_curriculum_id)
                                ->where('section',$course->section)
                                ->where('course_id',$course->course_id)
                                ->get();
        if(count($offered_course_ids_list)>0){
            foreach($offered_course_ids_list as $list){
                if(count($list->schedule)>0){
                    foreach($list->schedule as $row){
                        foreach($row->days as $day){
                            $days[] = $day->day;
                        }
                        $offered_schedule_ids = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
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
                        $offered_schedule_ids_open = EducOfferedCourses::whereIn('id',$offered_course_ids)
                                            ->whereNotIn('id',$offered_schedule_ids)
                                            ->where('year_level',$course->year_level)
                                            ->whereHas('course', function ($query) use ($course) {
                                                $query->where('name','LIKE','%'.$course->course->name.'%');
                                            })
                                            ->whereHas('schedule', function ($query) use ($course) {
                                                $query->where('id','<>',NULL);
                                            })
                                            ->pluck('id')->toArray();
                        $offered_course_idss = array_merge($offered_course_idss,$offered_schedule_ids_open);
                    }
                }
            }
        }
        $query = EducOfferedCourses::whereIn('id',$offered_course_idss)->get()
                        ->map(function($query) use ($name_services) {
                            $schedule = 'TBA';
                            $room = 'TBA';
                            $instructor = 'TBA';
                            if($query->instructor_id!=NULL){
                                $instructor = $name_services->firstname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                            }
                            if(count($query->schedule)>0){
                                foreach($query->schedule as $row){
                                    $days = array();
                                    if($row->room_id==NULL){
                                        $rm = 'TBA';
                                    }else{
                                        $rm = $row->room->name;
                                    }
                                    foreach($row->days as $day){
                                        $days[] = $day->day;
                                    }
                                    $days1 = implode('',$days);
                                    $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                                        date('h:ia',strtotime($row->time_to)).' '.$days1;
                                    $rooms[] = $rm;
                                }
                                $schedule = implode('<br>',$schedules);
                                $room = implode('<br>',$rooms);
                            }
                            return [
                                'id' => $query->id,
                                'course_id' => $query->course_id,
                                'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                                'code' => $query->code,
                                'name' => $query->course->name,
                                'units' => $query->course->units,
                                'section' => $query->section_code,
                                'schedule' => $schedule,
                                'room' => $room,
                                'instructor' => $instructor
                            ];
                        })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['section'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['name'];
                $data_list['f6'] = $r['units'];
                $data_list['f7'] = $r['schedule'];
                $data_list['f8'] = $r['room'];
                $data_list['f9'] = $r['instructor'];
                $data_list['f10'] = '<input type="radio" class="form-control anotherCourseSelected" value="'.$r['id'].'" data-id="'.$r['course_id'].'">';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function enrollmentViewTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $curriculum_id = $request->curriculum;
        $section_id = $request->section;
        if($curriculum_id==''){
            $curriculum = EducOfferedCurriculum::where('offered_program_id',$id)->orderBy('curriculum_id')->first();
            $curriculum_id = $curriculum->id;
        }
        if($section_id==''){
            $section = EducOfferedCourses::whereHas('curriculum', function ($query) use ($id) {
                        $query->where('offered_program_id',$id);
                    })->select('section')
                    ->groupBy('section')
                    ->orderBy('section')
                    ->first();
            $section_id = $section->section;
        }
        
        $courses_ids = EducOfferedCourses::where('offered_curriculum_id',$curriculum_id)
                    ->where('section',$section_id)->pluck('id')->toArray();
        $student_ids = StudentsCourses::whereIn('offered_course_id',$courses_ids)
                    ->pluck('user_id')->toArray();
        $query = StudentsInfo::whereIn('user_id',$student_ids)
                    ->get()
                    ->map(function($query) use ($name_services,$courses_ids) {
                        $name = $name_services->lastname($query->info->lastname,$query->info->firstname,$query->info->middlename,$query->info->extname);
                        $student_program = StudentsCourses::whereIn('offered_course_id',$courses_ids)
                                    ->select('student_program_id')
                                    ->where('user_id',$query->user_id)->first();
                        return [
                            'id' => $query->user_id,
                            'id_no' => $query->id_no,
                            'name' => $name,
                            'grade_level' => $query->grade_level->name,
                            'status' => $query->status->name,
                            'student_program_id' => $student_program->student_program_id
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['id_no'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['grade_level'];
                $data_list['f5'] = $r['status'];
                $data_list['f6'] = '<button class="btn btn-info btn-info-scan coursesViewModal"
                                        data-id="'.$r['id'].'"
                                        data-spid="'.$r['student_program_id'].'">
                                        <span class="fa fa-book"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function coursesViewTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $spid = $request->spid;
        $school_year_id = $request->school_year_id;
        $query = StudentsCourses::where('student_program_id',$spid)
                        ->where('school_year_id',$school_year_id)
                        ->get()
                        ->map(function($query) use ($name_services) {
                            $schedule = 'TBA';
                            $room = 'TBA';
                            $instructor = 'TBA';
                            if($query->instructor_id!=NULL){
                                $instructor = $name_services->firstname($query->course->instructor->lastname,
                                            $query->course->instructor->firstname,
                                            $query->course->instructor->middlename,
                                            $query->course->instructor->extname);
                            }
                            if(count($query->course->schedule)>0){
                                foreach($query->course->schedule as $row){
                                    $days = array();
                                    if($row->room_id==NULL){
                                        $rm = 'TBA';
                                    }else{
                                        $rm = $row->room->name;
                                    }
                                    foreach($row->days as $day){
                                        $days[] = $day->day;
                                    }
                                    $days1 = implode('',$days);
                                    $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                                        date('h:ia',strtotime($row->time_to)).' '.$days1;
                                    $rooms[] = $rm;
                                }
                                $schedule = implode('<br>',$schedules);
                                $room = implode('<br>',$rooms);
                            }
                            if($query->status->name==NULL){
                                $status = 'Ongoing';
                            }else{
                                $status = $query->status->name;
                            }
                            return [
                                'id' => $query->id,
                                'program' => $query->course->curriculum->offered_program->name.'-'.
                                            $query->course->curriculum->offered_program->program->shorten,
                                'code' => $query->course->code,
                                'grade_level' => $query->course->course->grade_level->name,
                                'units' => $query->course->course->units,
                                'section' => $query->course->section,
                                'schedule' => $schedule,
                                'room' => $room,
                                'instructor' => $instructor,
                                'curriculum' => $query->course->curriculum->curriculum->year_from.'-'.
                                                $query->course->curriculum->curriculum->year_to.' ('.
                                                $query->course->curriculum->code.')',
                                'status' => $status
                            ];
                        })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['grade_level'];
                $data_list['f6'] = $r['section'];
                $data_list['f7'] = $r['units'];
                $data_list['f8'] = $r['schedule'];
                $data_list['f9'] = $r['room'];
                $data_list['f10'] = $r['instructor'];
                $data_list['f11'] = $r['status'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>