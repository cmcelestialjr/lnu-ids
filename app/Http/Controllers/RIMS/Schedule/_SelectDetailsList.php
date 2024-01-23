<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducDay;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class _SelectDetailsList extends Controller
{
    public function selectDay(Request $request){
        $search = $request->input('search');
        $select_days = $request->select_days;
        $select_time = $request->select_time;
        $available_days_get = $this->getDayTime($request);
        if($select_time=='TBA'){
            $available_days = $available_days_get['day_available'];
        }else{
            $available_days = $available_days_get['time_day_available'][$select_time];
        }
        if($select_days==NULL){
            $select_days = [];
        }
        $results = EducDay::whereIn('no',$available_days)
            ->whereNotIn('no',$select_days)
            ->where('name','LIKE','%'.$search.'%')
            ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->no, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
    public function selectTime(Request $request){
        $search = $request->input('search');        
        $select_days = $request->select_days;
        $select_time = $request->select_time;
        $available_days_get = $this->getDayTime($request);
        if($select_days){
            foreach ($select_days as $day) {  
                $day_time[] = $available_days_get['day_time_available'][$day];              
                foreach($available_days_get['day_time_available'][$day] as $time){
                    $available_time[] = $time;
                }
            }
            if (count($day_time) >= 2) {
                $available_time = array_intersect($day_time[0], $day_time[1]);                
                for ($i = 2; $i < count($day_time); $i++) {
                    $available_time = array_intersect($available_time, $day_time[$i]);
                }
            }
            $available_time = array_unique($available_time);
        }else{
            $available_time = array_unique($available_days_get['time_available']);
        }
        $data[] = ['id' => 'TBA', 'text' => 'TBA'];
        foreach ($available_time as $result) {
            if($select_time!=$result){
                if($search!=''){
                    if (strpos($result, $search) !== false) {
                        $data[] = ['id' => $result, 'text' => $result];
                    }
                }else{
                    $data[] = ['id' => $result, 'text' => $result];
                }
            }            
        }
        return response()->json($data);
    }
    private function getDayTime($request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;        
        $select_hours = $request->select_hours;
        $select_minutes = $request->select_minutes;
        $instructor_id = $request->instructor_id;
        $room_id = $request->room_id;

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year')->where('id',$id)->first();
        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $section_code = $course->section_code;
        $offered_curriculum_id = $course->offered_curriculum_id;
        $year_level = $course->year_level;   
        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));

        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);

        $instructor_id = ($instructor_id=='TBA') ? NULL : $instructor_id;
        $room_id = ($room_id=='TBA') ? NULL : $room_id;
        $course_room = NULL;
        $course_instructor = NULL;
        $room_schedule_conflict = NULL;
        $instructor_schedule_conflict = NULL;        
        
        $course_schedule = EducOfferedSchedule::with('days','course.course')
            ->where('offered_course_id',$schedule_id)
            ->orderBy('time_from','ASC')->first(); 

        $datas['id'] = $id;
        $datas['school_year_id'] = $school_year_id;
        $datas['schedule_id'] = $schedule_id;
        $datas['room_id'] = $room_id;
        $datas['schedule'] = $course_schedule;        
        $datas['instructor_id'] = $instructor_id;
        $datas['section_code'] = $section_code;
        $datas['offered_curriculum_id'] = $offered_curriculum_id;
        $datas['year_level'] = $year_level;

        $course_schedule_others = $this->course_schedule_others($datas);
        $course_section = $this->course_section($datas);        
        
        if($course_schedule){

            foreach ($course_schedule->days as $day) {
                $schedule_days[] = $day->day;
            }
            
            $datas['schedule_days'] = $schedule_days;
            $room_id = $course_schedule->room_id;

            if($room_id){
                $room_schedule_conflict = $this->room_schedule_conflict($datas);
            }

            if($instructor_id){
                $instructor_schedule_conflict = $this->instructor_schedule_conflict($datas);
            }
        }

        if($room_id){
            $course_room = $this->course_room($datas);
        }
        
        if($instructor_id){
            $course_instructor = $this->course_instructor($datas);            
        }

        foreach ($time_period as $time){
            $time_list = $time->format('h:ia');
            $time_check['0'] = 0;
            $time_check['1'] = 0;
            $time_check['2'] = 0;
            $time_check['3'] = 0;
            $time_check['4'] = 0;
            $time_check['5'] = 0;
            $time_check['6'] = 0;

            if($course_room){
                if($course_room->count()>0){
                    foreach ($course_room as $row) {
                        if($row->schedule){
                            foreach ($row->schedule as $sched) {
                                $sched_time_from = date('h:ia',strtotime($sched->time_from));
                                if($sched->time_to){
                                    $sched_time_to = date('h:ia',strtotime($sched->time_to));
                                }else{
                                    $sched_time_to = '';
                                }
                                foreach ($sched->days as $day) {
                                    if($day->no=='7'){
                                        $day_no = 0;
                                    }else{
                                        $day_no = $day->no;
                                    }
                                    if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                        $time_check[$day_no] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($course_instructor){
                if($course_instructor->count()>0){
                    foreach ($course_instructor as $row) {
                        if($row->schedule){
                            foreach ($row->schedule as $sched) {
                                $sched_time_from = date('h:ia',strtotime($sched->time_from));
                                if($sched->time_to){
                                    $sched_time_to = date('h:ia',strtotime($sched->time_to));
                                }else{
                                    $sched_time_to = '';
                                }
                                foreach ($sched->days as $day) {
                                    if($day->no=='7'){
                                        $day_no = 0;
                                    }else{
                                        $day_no = $day->no;
                                    }
                                    if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                        $time_check[$day_no] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($course_section->count()>0){
                foreach ($course_section as $row) {
                    if($row->schedule){                        
                        foreach ($row->schedule as $sched) {
                            $sched_time_from = date('h:ia',strtotime($sched->time_from));
                            if($sched->time_to){
                                $sched_time_to = date('h:ia',strtotime($sched->time_to));
                            }else{
                                $sched_time_to = '';
                            }
                            foreach ($sched->days as $day) {
                                if($day->no=='7'){
                                    $day_no = 0;
                                }else{
                                    $day_no = $day->no;
                                }
                                if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                    $time_check[$day_no] = 1;
                                }
                            }
                        }
                    }
                }
            }

            if($course_schedule_others->count()>0){
                foreach ($course_schedule_others as $row) {
                    $sched_time_from = date('h:ia',strtotime($row->time_from));
                    if($row->time_to){
                        $sched_time_to = date('h:ia',strtotime($row->time_to));
                    }else{
                        $sched_time_to = '';
                    }
                    foreach ($row->days as $day) {
                        if($day->no=='7'){
                            $day_no = 0;
                        }else{
                            $day_no = $day->no;
                        }
                        if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                            $time_check[$day_no] = 1;
                        }
                    }
                }
            }

            if($room_schedule_conflict){
                if($room_schedule_conflict->count()>0){
                    foreach ($room_schedule_conflict as $sched) {
                        $sched_time_from = date('h:ia',strtotime($sched->time_from));
                        if($sched->time_to){
                            $sched_time_to = date('h:ia',strtotime($sched->time_to));
                        }else{
                            $sched_time_to = '';
                        }
                        foreach ($sched->days as $day) {
                            if($day->no=='7'){
                                $day_no = 0;
                            }else{
                                $day_no = $day->no;
                            }
                            if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                $time_check[$day_no] = 1;
                            }
                        }
                    }
                }
            }

            if($instructor_schedule_conflict){
                if($instructor_schedule_conflict->count()>0){                    
                    foreach ($instructor_schedule_conflict as $sched) {
                        $sched_time_from = date('h:ia',strtotime($sched->time_from));
                        if($sched->time_to){
                            $sched_time_to = date('h:ia',strtotime($sched->time_to));
                        }else{
                            $sched_time_to = '';
                        }
                        foreach ($sched->days as $day) {
                            if($day->no=='7'){
                                $day_no = 0;
                            }else{
                                $day_no = $day->no;
                            }
                            if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                $time_check[$day_no] = 1;
                            }
                        }
                    }
                }
            }

            for ($i=0; $i < 7; $i++) { 
                if($time_check[$i]==0){
                    $time_day[$i][] = $time_list;
                }
            }
        }
        $day_available = [];
        $time_available = [];
        $day_time_available = [];
        $time_day_available = [];
        for ($h=0; $h < 7; $h++) { 
            $times = $time_day[$h];
            $count = count($times);
            for ($i = 0; $i < $count - 1; $i++) {
                $time_next = $times[$i+1];
                $time_1 = strtotime($times[$i]);
                $time_2 = strtotime($time_next);            
                $diff_in_seconds_ = abs($time_2 - $time_1);
                $diff_in_minutes_ = $diff_in_seconds_ / 60;
                $diff_in_minutes_total = 15;
                $x = 1;
                for ($j = $i + 1; $j < $count; $j++) {
                    $time1 = strtotime($times[$i]);
                    $time2 = strtotime($times[$j]);                
                    $diff_in_seconds = abs($time2 - $time1);
                    $diff_in_minutes = $diff_in_seconds / 60;
                    $diff_in_hours = $diff_in_seconds / 3600;
        
                    if (in_array($times[$j], $time_day[$h]) && $diff_in_minutes==$x*15){
                        if ($diff_in_minutes_ == 15) {
                            if ($diff_in_hours == $select_hours+($select_minutes/60)) {
                                if($h==0){
                                    $day = 7;
                                }else{
                                    $day = $h;
                                }
                                $day_available[] = $day;                                
                                $day_time_available[$day][] = $times[$i].'-'.$times[$j];
                                $time_available[] = $times[$i].'-'.$times[$j];
                                $time_day_available[$times[$i].'-'.$times[$j]][] = $day;
                            }
                        }
                    }
                    $x++;
                    $diff_in_minutes_total+=$diff_in_minutes;
                }
            }
        }
        return array(
                'day_available' => $day_available,
                'day_time_available' => $day_time_available,
                'time_available' => $time_available,
                'time_day_available' => $time_day_available
            );
    }
    private function course_schedule_others($datas){
        $id = $datas['id'];
        $schedule_id = $datas['schedule_id'];
        return EducOfferedSchedule::with('days','course.course')
            ->where('offered_course_id',$id)
            ->where('id','<>',$schedule_id)
            ->orderBy('time_from','ASC')->get();
    }
    private function course_section($datas){
        $id = $datas['id'];
        $offered_curriculum_id = $datas['offered_curriculum_id'];
        $year_level = $datas['year_level'];
        $section_code = $datas['section_code'];
        return EducOfferedCourses::with('schedule.days','course')
            ->where('offered_curriculum_id',$offered_curriculum_id)
            ->where('year_level',$year_level)
            ->where('section_code',$section_code)
            ->where('id','<>',$id)
            ->get();
    }
    private function course_room($datas){
        $school_year_id = $datas['school_year_id'];
        $room_id = $datas['room_id'];
        $section_code = $datas['section_code'];
        return EducOfferedCourses::with('schedule.days','course')
            ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                $subQuery->where('school_year_id', $school_year_id);
            })
            ->whereHas('schedule', function ($query) use ($room_id) {
                $query->where('room_id', $room_id);
            })
            ->where('section_code','<>',$section_code)
            ->get();         
    }
    private function course_instructor($datas){
        $school_year_id = $datas['school_year_id'];
        $instructor_id = $datas['instructor_id'];
        $section_code = $datas['section_code'];
        return EducOfferedCourses::with('schedule.days','course')
            ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                $subQuery->where('school_year_id', $school_year_id);
            })
            ->where('section_code','<>',$section_code)
            ->where('instructor_id',$instructor_id)
            ->get();
    } 
    private function room_schedule_conflict($datas){
        $school_year_id = $datas['school_year_id'];
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
        $school_year_id = $datas['school_year_id'];
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
}