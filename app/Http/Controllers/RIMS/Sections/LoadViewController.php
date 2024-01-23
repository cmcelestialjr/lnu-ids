<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedRoom;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\EducPrograms;
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
        $branch = EducBranch::get();
        $program = EducPrograms::whereHas('offered_program', function ($query) use ($id) {
                $query->where('school_year_id', $id);
            })->get();
        $data = array(
            'id' => $id,
            'branch' => $branch,
            'program' => $program
        );
        return view('rims/sections/programsSelect',$data);
    }
    public function gradeLevelSelect(Request $request){
        $id = $request->id;
        $grade_level = EducYearLevel::
            whereHas('courses.courses', function ($subQuery) use ($id) {
                $subQuery->where('offered_curriculum_id', $id);
            })->get();
        $data = array(
            'grade_level' => $grade_level
        );
        return view('rims/sections/gradeLevelSelect',$data);
    }
    public function courseSchedRmTable(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $data = array();
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $room_id = $request->room_id;
        $instructor_id = $request->instructor_id;

        $query = EducOfferedCourses::with('curriculum.offered_program.school_year')->where('id',$id)->first();
        $school_year_id = $query->curriculum->offered_program->school_year_id;
        $instructor_course = $query->instructor_id;
        $section_code = $query->section_code;
        $offered_curriculum_id = $query->offered_curriculum_id;
        $year_level = $query->year_level;
        $time_from = $query->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($query->curriculum->offered_program->school_year->time_to)));

        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);

        $schedule = NULL;
        $course_room = NULL;
        $course_instructor = NULL;
        $room_schedule_conflict = NULL;
        $instructor_schedule_conflict = NULL;
        $room_id_course_get = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from','ASC')->first();
        
        $course_curriculum = EducOfferedCourses::with('schedule.days','course')
                                ->where('offered_curriculum_id',$offered_curriculum_id)
                                ->where('year_level',$year_level)
                                ->where('section_code',$section_code)
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

        if($room_id!=NULL){            
            $course_room = EducOfferedCourses::with('schedule.days','course')
                            ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                $subQuery->where('school_year_id', $school_year_id);
                            })
                            ->whereHas('schedule', function ($query) use ($room_id) {
                                $query->where('room_id', $room_id);
                            })
                            ->where('section_code','<>',$section_code)
                            ->get();
        }
        if($instructor_id!=NULL){
            $course_instructor = EducOfferedCourses::with('schedule.days','course')
                                ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                    $subQuery->where('school_year_id', $school_year_id);
                                })
                                ->where('section_code','<>',$section_code)
                                ->where('instructor_id',$instructor_id)
                                ->get();
        }

        if($schedule_id==NULL || $schedule_id=='new'){
            if($schedule_id!='new'){
                $schedule = EducOfferedSchedule::with('days','course.course')->where('offered_course_id',$id)->orderBy('time_from','ASC')->first();
                $this_offered_schedule = EducOfferedSchedule::with('days','course.course')
                                            ->where('offered_course_id',$id)
                                            ->where('id','<>',$this_schedule_id)
                                            ->orderBy('time_from','ASC')->get();
            }else{
                $this_offered_schedule = EducOfferedSchedule::with('days','course')
                                            ->where('offered_course_id',$id)                                        
                                            ->orderBy('time_from','ASC')->get();
            }
            
        }else{
            $schedule = EducOfferedSchedule::with('days','course.course')->where('id',$schedule_id)->orderBy('time_from','ASC')->first();
            $this_offered_schedule = EducOfferedSchedule::with('days','course.course')
                                        ->where('offered_course_id',$id)
                                        ->where('id','<>',$schedule_id)
                                        ->orderBy('time_from','ASC')->get();
        }
        if($schedule!=NULL){
            $schedule_days = EducOfferedScheduleDay::where('offered_schedule_id',$schedule->id)->pluck('day')->toArray();
            $datas['id'] = $id;
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
        $school_year_id = $datas['id'];
        $schedule_id = $datas['schedule_id'];
        $room_id = $datas['room_id'];
        $schedule = $datas['schedule'];
        $schedule_days = $datas['schedule_days'];
        $room_schedule_conflict = EducOfferedSchedule::with('days','course.course')
                                            ->whereHas('course.curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                                $subQuery->where('school_year_id', $school_year_id);
                                            })
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
        $school_year_id = $datas['id'];
        $schedule_id = $datas['schedule_id'];
        $schedule = $datas['schedule'];
        $schedule_days = $datas['schedule_days'];
        $instructor_id = $datas['instructor_id'];
        $instructor_schedule_conflict = EducOfferedSchedule::with('days','course.course')
                                            ->whereHas('course.curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                                $subQuery->where('school_year_id', $school_year_id);
                                            })
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