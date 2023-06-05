<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedRoom;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\EducRoom;
use App\Models\EducYearLevel;
use App\Models\UsersRoleList;
use App\Services\NameServices;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class LoadViewController extends Controller
{
    public function programsSelect(Request $request){
        $id = $request->id;
        $query = EducOfferedPrograms::with('department','program')->where('school_year_id',$id)->get();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/sections/programsSelect',$data);
    }
    public function gradeLevelSelect(Request $request){
        $id = $request->id;
        $offered_courses_ids = EducOfferedCourses::where('offered_curriculum_id',$id)->pluck('course_id')->toArray();
        $courses_grade_level_id = EducCourses::whereIn('id',$offered_courses_ids)->pluck('grade_level_id')->toArray();
        $grade_level = EducYearLevel::whereIn('id',$courses_grade_level_id)->get();
        $data = array(
            'grade_level' => $grade_level
        );
        return view('rims/sections/gradeLevelSelect',$data);
    }
    public function courseSchedRmDetails(Request $request){
        $name_services = new NameServices;
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)->first();        
        $no_students = count($query->students);
        $schedule = 'TBA';
        $room = 'TBA';
        $instructor = 'TBA';
        if($query->instructor_id!=NULL){
            $instructor = $name_services->lastname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
        }
        if(count($query->schedule)>0){
            foreach($query->schedule as $row){    
                $days = array();
                foreach($row->days as $day){
                    $days[] = $day->day;
                }
                $days1 = implode('',$days);
                $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
                if($row->room_id==NULL){
                    $rooms[] = 'TBA';
                }else{
                    $rooms[] = $row->room->name;
                }
            }
            $schedule = implode('<br>',$schedules);
            $room = implode('<br>',$rooms);
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'schedule' => $schedule,
            'room' => $room,
            'instructor' => $instructor,
            'no_students' => $no_students,
        );
        return view('rims/sections/courseSchedRmDetails',$data);
    }
    public function courseSchedRmSchedule(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $query = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from')->get();
        if($schedule_id=='new'){
            $selected1 = 'selected';
            $selected2 = '';
        }else{
            $selected1 = '';
            $selected2 = 'selected';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'selected1' => $selected1,
            'selected2' => $selected2,
            'schedule_id' => $schedule_id
        );
        return view('rims/sections/courseSchedRmSchedule',$data);
    }
    public function courseSchedRmInstructor(Request $request){
        $name_services = new NameServices;
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        if($schedule_id==NULL){
            $schedule = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from')->first();
            if($schedule!=NULL){
                $schedule_id = $schedule->id;
            }
        }
        $query = EducOfferedCourses::where('id',$id)->first();
        $room = EducOfferedSchedule::where('id',$schedule_id)->first();
        if($room!=NULL){
            $hours = $room->hours;
            $minutes = $room->minutes;
            $room_id = $room->room_id;
            $time_sched = date('h:ia',strtotime($room->time_from)).'-'.date('h:ia',strtotime($room->time_to));
            $days_sched = EducOfferedScheduleDay::where('offered_schedule_id',$room->id)->get();
            if($room->type=='Lab'){
                $lec = '';
                $lab = 'checked';
            }else{
                $lec = 'checked';
                $lab = '';                
            }
        }else{
            $room_id = '';
            $time_sched = '';
            $days_sched = '';
            $lec = 'checked';
            $lab = '';
            $hours = $query->hours;
            $minutes = $query->minutes;
        }        
        $instructors = UsersRoleList::where('role_id',3)->where('user_id',$query->instructor_id)->first();
        if($room_id==''){
            $rooms = NULL;
        }else{
            $rooms = EducRoom::where('id',$room_id)->first();
        }
        $minutes_list = array(0,15,30,45);
        $data = array(
            'id' => $id,
            'query' => $query,
            'hours' => $hours,
            'minutes' => $minutes,
            'minutes_list' => $minutes_list,
            'rooms' => $rooms,
            'room_id' => $room_id,
            'time_sched' => $time_sched,
            'days_sched' => $days_sched,
            'schedule_id' => $schedule_id,
            'instructors' => $instructors,
            'instructor_id' => $query->instructor_id,
            'name_services' => $name_services,
            'lec' => $lec,
            'lab' => $lab
        );
        return view('rims/sections/courseSchedRmInstructor',$data);
    }
    public function courseSchedRmTable(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $data = array();
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $room_id = $request->room_id;
        $instructor_id = $request->instructor_id;
        $query = EducOfferedCourses::where('id',$id)->first();
        $school_year_id = $query->curriculum->offered_program->school_year_id;
        $time_from = $query->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($query->curriculum->offered_program->school_year->time_to)));
        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);
        $x = 0;
        $schedule = NULL;
        $course_room = NULL;
        $course_instructor = NULL;
        $room_schedule_conflict = NULL;
        $instructor_schedule_conflict = NULL;
        $room_id_course_get = EducOfferedSchedule::where('offered_course_id',$query->id)->orderBy('time_from','ASC')->first();
        $instructor_course = $query->instructor_id;
        $course_curriculum = EducOfferedCourses::where('offered_curriculum_id',$query->offered_curriculum_id)
                                ->where('year_level',$query->year_level)
                                ->where('section',$query->section)
                                ->where('id','<>',$id)
                                ->get();
        $room_id_course = NULL;
        $this_schedule_id = NULL;
        if($room_id_course_get!=NULL){
            $room_id_course = $room_id_course_get->room_id;
            $this_schedule_id = $room_id_course_get->id;
        }
        if(($room_id==NULL && $room_id_course==NULL) || $room_id=='TBA'){
            $room_id = NULL;
        }else{
            $room_id = $room_id_course;
        }
        if($request->room_id!=NULL && $request->room_id!='TBA'){
            $room_id = $request->room_id;
        }
        if(($instructor_id==NULL && $instructor_course==NULL) || $instructor_id=='TBA'){
            $instructor_id = NULL;
        }else{
            $instructor_id = $instructor_course;
        }
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)->pluck('id')->toArray();
        $offered_course_id_sec_ins = EducOfferedCourses::where('offered_curriculum_id',$query->offered_curriculum_id)
                                                ->where('section',$query->section)->pluck('id')->toArray();
        if($room_id!=NULL){            
            $course_room = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                            ->whereHas('schedule', function ($query) use ($room_id) {
                                $query->where('room_id', $room_id);
                            })
                            ->whereNotIn('id',$offered_course_id_sec_ins)
                            ->get();
        }
        if($instructor_id!=NULL){
            $course_instructor = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                    ->whereNotIn('id',$offered_course_id_sec_ins)
                                    ->where('instructor_id',$instructor_id)
                                    ->get();
        }
        if($schedule_id==NULL || $schedule_id=='new'){
            if($schedule_id!='new'){
                $schedule = EducOfferedSchedule::where('offered_course_id',$query->id)->orderBy('time_from','ASC')->first();
                $this_offered_schedule = EducOfferedSchedule::where('offered_course_id',$query->id)
                                            ->where('id','<>',$this_schedule_id)
                                            ->orderBy('time_from','ASC')->get();
            }else{
                $this_offered_schedule = EducOfferedSchedule::where('offered_course_id',$query->id)                                        
                                            ->orderBy('time_from','ASC')->get();
            }
            
        }else{
            $schedule = EducOfferedSchedule::where('id',$schedule_id)->orderBy('time_from','ASC')->first();
            $this_offered_schedule = EducOfferedSchedule::where('offered_course_id',$query->id)
                                        ->where('id','<>',$schedule_id)
                                        ->orderBy('time_from','ASC')->get();
        }
        if($schedule!=NULL){
            $schedule_days = EducOfferedScheduleDay::where('offered_schedule_id',$schedule->id)->pluck('day')->toArray();
            $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)->pluck('id')->toArray();
            $datas['offered_course_ids'] = $offered_course_ids;
            $datas['schedule_id'] = $schedule_id;
            $datas['room_id'] = $request->room_id;
            $datas['schedule'] = $schedule;
            $datas['schedule_days'] = $schedule_days;
            $datas['instructor_id'] = $request->instructor_id;
            if($request->room_id!=NULL && $request->room_id!='TBA'){
                $room_schedule_conflict = $this->room_schedule_conflict($datas);
            }
            if($request->instructor_id!=NULL && $request->instructor_id!='TBA'){
                $instructor_schedule_conflict = $this->instructor_schedule_conflict($datas);
            }
            $scheduleRemoveDayTr = '';
            $hours = $schedule->hours;
            $minutes = $schedule->minutes;
        }else{
            $scheduleRemoveDayTr = 'hide';
            $hours = $query->hours;
            $minutes = $query->minutes;
        }
        
        $data = array(
            'hours' => $hours,
            'minutes' => $minutes,            
            'time_period' => $time_period,
            'schedule' => $schedule,
            'course_curriculum' => $course_curriculum,
            'course_room' => $course_room,
            'course_instructor' => $course_instructor,
            'time_from' => $time_from,
            'rschedule_id' => $request->schedule_id,
            'room_schedule_conflict' => $room_schedule_conflict,
            'instructor_schedule_conflict' => $instructor_schedule_conflict,
            'this_offered_schedule' => $this_offered_schedule,
            'scheduleRemoveDayTr' => $scheduleRemoveDayTr
        );
        return view('rims/sections/courseSchedRmTable',$data);
    }
    private function room_schedule_conflict($datas){
        $offered_course_ids = $datas['offered_course_ids'];
        $schedule_id = $datas['schedule_id'];
        $room_id = $datas['room_id'];
        $schedule = $datas['schedule'];
        $schedule_days = $datas['schedule_days'];
        $room_schedule_conflict = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
                                            ->where('id','<>',$schedule_id)
                                            ->where('room_id',$room_id)
                                            ->where(function ($query) use ($schedule) {
                                                $query->where(function ($query) use ($schedule) {
                                                    $query->where('time_from','>=',$schedule->time_from)
                                                    ->where('time_to','<=',$schedule->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','<=',$schedule->time_from)
                                                    ->where('time_to','>',$schedule->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','<',$schedule->time_to)
                                                    ->where('time_to','>=',$schedule->time_to);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','>=',$schedule->time_from)
                                                    ->where('time_to','<=',$schedule->time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($schedule_days) {
                                                $query->whereIn('day', $schedule_days);
                                            })
                                            ->get();
        return $room_schedule_conflict;
    }
    private function instructor_schedule_conflict($datas){
        $offered_course_ids = $datas['offered_course_ids'];
        $schedule_id = $datas['schedule_id'];
        $schedule = $datas['schedule'];
        $schedule_days = $datas['schedule_days'];
        $instructor_id = $datas['instructor_id'];
        $instructor_schedule_conflict = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
                                            ->where('id','<>',$schedule_id)
                                            ->where(function ($query) use ($schedule) {
                                                $query->where(function ($query) use ($schedule) {
                                                    $query->where('time_from','>=',$schedule->time_from)
                                                    ->where('time_to','<=',$schedule->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','<=',$schedule->time_from)
                                                    ->where('time_to','>',$schedule->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','<',$schedule->time_to)
                                                    ->where('time_to','>=',$schedule->time_to);
                                                });
                                                $query->orWhere(function ($query) use ($schedule) {
                                                    $query->where('time_from','>=',$schedule->time_from)
                                                    ->where('time_to','<=',$schedule->time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($schedule_days) {
                                                $query->whereIn('day', $schedule_days);
                                            })
                                            ->whereHas('course', function ($query) use ($instructor_id) {
                                                $query->where('instructor_id', $instructor_id);
                                            })
                                            ->get();
        return $instructor_schedule_conflict;
    }
    private function days_list($days_sched){
        $days_array = array('SU','M','T','W','TH','F','S');
        return $days_array;
    }
}