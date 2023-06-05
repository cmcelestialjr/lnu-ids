<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function courseSchedRmInstructorUpdate(Request $request){        
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $schedule_id = $request->schedule_id;
        $sched_name = 'New';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $room_id = $request->room_id;
            $instructor_id = $request->instructor_id;            
            try{
                $room_schedule = 'success';
                $instructor_schedule = 'success';
                $instructor_schedule_check = NULL;
                $result = 'success';
                if($instructor_id=='TBA'){
                    $instructor_id = NULL;
                }
                if($room_id=='TBA'){
                    $room_id = NULL;
                }
                $schedule_id = $this->schedDayTime($request,$updated_by);
                if($schedule_id!='new'){
                    $course = EducOfferedCourses::where('id',$id)->first();
                    $schedule = EducOfferedSchedule::where('id',$schedule_id)->first();                    
                    $school_year_id = $course->curriculum->offered_program->school_year_id;
                    $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)->pluck('id')->toArray();
                    $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)->pluck('id')->toArray();
                    $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)->pluck('id')->toArray();
                    if($schedule==NULL){
                        $schedule_days = NULL;
                    }else{
                        $schedule_days = EducOfferedScheduleDay::where('offered_schedule_id',$schedule->id)->pluck('day')->toArray();                        
                    }
                    $datas['offered_course_ids'] = $offered_course_ids;
                    $datas['schedule_id'] = $schedule_id;
                    $datas['room_id'] = $room_id;
                    $datas['schedule'] = $schedule;
                    $datas['time_from'] = $schedule->time_from;
                    $datas['time_to'] = $schedule->time_to;
                    $datas['schedule_days'] = $schedule_days;
                    $datas['room_schedule'] = $room_schedule;
                    if($schedule!=NULL){
                        $sched_name = date('h:ia',strtotime($schedule->time_from)).'-'.date('h:ia',strtotime($schedule->time_to));
                        if($room_id!=NULL){
                            $room_schedule = $this->room_schedule_check($datas);
                        }
                        if($room_schedule=='success'){
                            EducOfferedSchedule::where('id', $schedule_id)
                                        ->update(['room_id' => $room_id,
                                                'updated_by' => $updated_by,
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                ]);
                        }
                    }
                    if($instructor_id!=NULL){
                        $datas['instructor_id'] = $instructor_id;
                        $instructor_schedule_check = $this->instructor_schedule_check($datas);
                    }
                }
                if($instructor_schedule_check!=NULL){
                    $instructor_schedule = 'error';
                }else{
                    EducOfferedCourses::where('id', $id)
                                ->update(['instructor_id' => $instructor_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        ]);
                }
                if($room_schedule=='error' && $instructor_schedule=='error'){
                    $result = 'Instructor & Room Conflict. Please check below';
                }elseif($room_schedule=='error' && $instructor_schedule=='success'){
                    $result = 'Room Conflict. Please check below';
                }elseif($room_schedule=='success' && $instructor_schedule=='error'){
                    $result = 'Instructor Conflict. Please check below';
                }
            }catch(Exception $e){
                        
            }
        }
        $response = array('result' => $result,
                          'schedule_id' => $schedule_id,
                          'sched_name' => $sched_name);
        return response()->json($response);
    }
    public function typeUpdate(Request $request){        
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $schedule_id = $request->schedule_id;
        $type = $request->type;
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                EducOfferedSchedule::where('id', $schedule_id)
                    ->update(['type' => $type,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                $result = 'success';
            }catch(Exception $e){
                        
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function minMaxSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $request->id;
        $min_student = $request->min_student;
        $max_student = $request->max_student;
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                EducOfferedCourses::where('id', $id)
                    ->update(['min_student' => $min_student,
                        'max_student' => $max_student,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                $result = 'success';
            }catch(Exception $e){
                        
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function scheduleTimeUpdate(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $time = date('H:i:s',strtotime($request->t));
        $time_from = date('H:i:s',strtotime($request->time_from));
        $time_to = date('H:i:s',strtotime($request->time_to));
        $type = $request->type;
        $day = $request->d;
        $room_id = $request->room_id;
        $hours = $request->hours;
        $minutes = $request->minutes;
        $result = 'error';
        $sched_name = NULL;
        $list_x = array();
        $e = '';
        $school_year_time_from = '';
        $school_year_time_to = '';
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $course = EducOfferedCourses::where('id',$id)->first();
            $school_year_time_from = $course->curriculum->offered_program->school_year->time_from;
            $school_year_time_to = $course->curriculum->offered_program->school_year->time_to;
            $x = 0;
            if($type!='Lec' && $type!='Lab'){
                $x++;
            }
            if($time_from>=$time_to){
                $result = 'time';
                $x++;
            }
            if($time_from<$school_year_time_from){
                $result = 'school_from';
                $x++;
            }
            if($time_to>$school_year_time_to){
                $result = 'school_to';
                $x++;
            }
            if($room_id=='TBA'){
                $room_id = NULL;
            }
            if($x==0){
                $user = Auth::user();
                $updated_by = $user->id;
                try{
                    if($schedule_id=='new'){
                        $insert = new EducOfferedSchedule(); 
                        $insert->offered_course_id = $id;
                        $insert->time_from = $time_from;
                        $insert->time_to = $time_to;
                        $insert->type = $type;
                        $insert->room_id = $room_id;
                        $insert->hours = $hours;
                        $insert->minutes = $minutes;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $schedule_id = $insert->id; 
                    }
                    $schedule = EducOfferedSchedule::where('id',$schedule_id)->first();
                    if($schedule->time_to!=NULL){
                        $schedule_days = EducOfferedScheduleDay::where('offered_schedule_id',$schedule->id)->pluck('day')->toArray();
                        $school_year_id = $schedule->course->curriculum->offered_program->school_year_id;
                        $room_id = $schedule->room_id;
                        $instructor_id = $schedule->course->instructor_id;
                        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)->pluck('id')->toArray();
                        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)->pluck('id')->toArray();
                        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)->pluck('id')->toArray();
                        $room_schedule = 'success';
                        $instructor_schedule_check = NULL;
                        $schedule_days[] = $this->daysLetter($day);
                        $datas['offered_course_ids'] = $offered_course_ids;
                        $datas['offered_course_id'] = $schedule->offered_course_id;
                        $datas['schedule_id'] = $schedule_id;
                        $datas['room_id'] = $room_id;
                        $datas['schedule'] = $schedule;
                        $datas['time_from'] = $time_from;
                        $datas['time_to'] = $time_to;
                        $datas['schedule_days'] = $schedule_days;
                        $datas['room_schedule'] = $room_schedule;
                        if($room_id!=NULL){
                            $room_schedule = $this->room_schedule_check($datas);
                        }
                        if($room_schedule!='success'){
                            $result = 'conflict';
                        }                
                        if($instructor_id!=NULL){
                            $datas['instructor_id'] = $instructor_id;
                            $instructor_schedule_check = $this->instructor_schedule_check($datas);
                        }
                        if($instructor_schedule_check!=NULL){
                            $result = 'conflict';
                        }
                        $schedule_course_self = $this->schedule_course_self($datas);
                        if($schedule_course_self!='success'){
                            $result = 'conflict';
                        }
                    }
                    if($result!='conflict'){
                        $check = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)
                                    ->where('no',$day)->first();
                        if($check==NULL){
                            $day_letter = $this->daysLetter($day);
                            $insert = new EducOfferedScheduleDay(); 
                            $insert->offered_schedule_id = $schedule_id;
                            $insert->day = $day_letter;
                            $insert->no = $day;
                            $insert->updated_by = $updated_by;
                            $insert->save();                    
                        }else{
                            if($schedule_id!='new'){
                                $check = EducOfferedSchedule::where('id',$schedule_id)->first();
                                if($check!=NULL){
                                    EducOfferedSchedule::where('id', $schedule_id)
                                            ->update(['time_from' => $time_from,
                                                    'time_to' => $time_to,
                                                    'type' => $type,
                                                    'room_id' => $room_id,
                                                    'hours' => $hours,
                                                    'minutes' => $minutes,
                                                    'updated_by' => $updated_by,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                }
                            }
                        }
                        $sched = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)
                                    ->where('no',$day)->first();
                        $time_from = $sched->schedule->course->curriculum->offered_program->school_year->time_from;
                        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($sched->schedule->course->curriculum->offered_program->school_year->time_to)));
                        $sched_time_to = '';
                        if($sched->schedule->time_to!=NULL){
                            $sched_time_to = '-'.date('h:ia',strtotime($sched->schedule->time_to));
                        }
                        $sched_name = date('h:ia',strtotime($sched->schedule->time_from)).$sched_time_to;
                        $start = new DateTime($time_from);
                        $end = new DateTime($time_to);
                        $interval = DateInterval::createFromDateString('15 minutes');
                        $time_period = new DatePeriod($start, $interval, $end);
                        $days_no = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->pluck('no')->toArray();
                        $x = 0;
                        
                        foreach ($time_period as $time){
                            $time_list = $time->format('h:ia');
                            $sched_time_from = date('h:ia',strtotime($sched->schedule->time_from));
                            $sched_time_to = date('h:ia',strtotime($sched->schedule->time_to));
                            foreach($days_no as $day_no){
                                if($sched_time_from==$time_list){
                                    $list_x[] = $x.$day_no.'_'.$time_list;
                                }
                                if($sched->schedule->time_to!=NULL){
                                    if($sched_time_to==$time_list){
                                        $list_x[] = $x.$day_no.'_'.$time_list;
                                    }
                                    if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                        $list_x[] = $x.$day_no.'_&nbsp;&nbsp;';
                                    }
                                }
                            }
                            $x++;
                        }
                        $result = 'success';
                    }else{
                        $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)
                                ->where('no',$day)->delete();
                        $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                        $check = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->first();
                        if($check==NULL){
                            $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                            $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                        }
                    }
                }catch(Exception $e){
                    
                }
            }
        }
        $response = array('result' => $result,
                          'sched_id' => $schedule_id,
                          'sched_name' => $sched_name,
                          'list_x' => $list_x,
                          'time_from' => date('h:ia',strtotime($school_year_time_from)),
                          'time_to' => date('h:ia',strtotime($school_year_time_to)));
        return response()->json($response);
    }
    private function schedule_course_self($datas){
        $offered_course_id = $datas['offered_course_id'];
        $schedule_id = $datas['schedule_id'];
        $room_id = $datas['room_id'];
        $schedule = $datas['schedule'];
        $time_from = $datas['time_from'];
        $time_to = $datas['time_to'];
        $schedule_days = $datas['schedule_days'];
        $room_schedule = $datas['room_schedule'];
        $room_schedule_check = EducOfferedSchedule::where('offered_course_id',$offered_course_id)
                                            ->where('id','<>',$schedule_id)
                                            ->where(function ($query) use ($time_from,$time_to) {
                                                $query->where(function ($query) use ($time_from) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_from) {
                                                    $query->where('time_from','<=',$time_from)
                                                    ->where('time_to','>',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_to) {
                                                    $query->where('time_from','<',$time_to)
                                                    ->where('time_to','>=',$time_to);
                                                });
                                                $query->orWhere(function ($query) use ($time_from,$time_to) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($schedule_days) {
                                                $query->whereIn('day', $schedule_days);
                                            })
                                            ->first();
        if($room_schedule_check!=NULL){
            $room_schedule = 'error';
        }
        return $room_schedule;
    }
    private function room_schedule_check($datas){
        $offered_course_ids = $datas['offered_course_ids'];
        $schedule_id = $datas['schedule_id'];
        $room_id = $datas['room_id'];
        $schedule = $datas['schedule'];
        $time_from = $datas['time_from'];
        $time_to = $datas['time_to'];
        $schedule_days = $datas['schedule_days'];
        $room_schedule = $datas['room_schedule'];
        $room_schedule_check = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
                                            ->where('id','<>',$schedule_id)
                                            ->where('room_id',$room_id)
                                            ->where(function ($query) use ($time_from,$time_to) {
                                                $query->where(function ($query) use ($time_from) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_from) {
                                                    $query->where('time_from','<=',$time_from)
                                                    ->where('time_to','>',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_to) {
                                                    $query->where('time_from','<',$time_to)
                                                    ->where('time_to','>=',$time_to);
                                                });
                                                $query->orWhere(function ($query) use ($time_from,$time_to) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($schedule_days) {
                                                $query->whereIn('day', $schedule_days);
                                            })
                                            ->first();
        if($room_schedule_check!=NULL){
            $room_schedule = 'error';
        }
        return $room_schedule;
    }
    private function instructor_schedule_check($datas){
        $offered_course_ids = $datas['offered_course_ids'];
        $schedule_id = $datas['schedule_id'];
        $time_from = $datas['time_from'];
        $time_to = $datas['time_to'];
        $schedule_days = $datas['schedule_days'];
        $instructor_id = $datas['instructor_id'];
        $instructor_schedule_check = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
                                            ->where('id','<>',$schedule_id)
                                            ->where(function ($query) use ($time_from,$time_to) {
                                                $query->where(function ($query) use ($time_from) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_from) {
                                                    $query->where('time_from','<=',$time_from)
                                                    ->where('time_to','>',$time_from);
                                                });
                                                $query->orWhere(function ($query) use ($time_to) {
                                                    $query->where('time_from','<',$time_to)
                                                    ->where('time_to','>=',$time_to);
                                                });
                                                $query->orWhere(function ($query) use ($time_from,$time_to) {
                                                    $query->where('time_from','>=',$time_from)
                                                    ->where('time_to','<=',$time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($schedule_days) {
                                                $query->whereIn('day', $schedule_days);
                                            })
                                            ->whereHas('course', function ($query) use ($instructor_id) {
                                                $query->where('instructor_id', $instructor_id);
                                            })
                                            ->first();
        return $instructor_schedule_check;
    }
    private function schedDayTime($request,$updated_by){
        $id = $request->id;
        $schedule_id = $request->schedule_id; 
        $type = $request->type;
        $hours = $request->hours;
        $minutes = $request->minutes;
        $check = EducOfferedSchedule::where('id',$schedule_id)->first();
        $x = 0;
        EducOfferedCourses::where('id', $id)
                        ->update(['hours' => $hours,
                                'minutes' => $minutes,
                                'updated_by' => $updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                        ]);
        if($check!=NULL){
            if($check->hours!=$request->hours || $check->minutes!=$request->minutes){
                EducOfferedSchedule::where('id', $schedule_id)
                        ->update(['offered_course_id' => $id,
                                'type' => $type,
                                'hours' => $hours,
                                'minutes' => $minutes,
                                'updated_by' => $updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                $schedule_id = 'new';
                $x++;
            }
        }
        if($x==0){
            if($request->days!='' && $request->time!='TBA'){
                $check = EducOfferedSchedule::where('id',$schedule_id)->first();
                $time = explode('-',$request->time);
                $time_from = date('H:i:s',strtotime($time[0]));
                $time_to = date('H:i:s',strtotime($time[1]));                           
                if($check!=NULL){
                    EducOfferedSchedule::where('id', $schedule_id)
                        ->update(['offered_course_id' => $id,
                                'time_from' => $time_from,
                                'time_to' => $time_to,
                                'type' => $type,
                                'hours' => $hours,
                                'minutes' => $minutes,
                                'updated_by' => $updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }else{
                    $insert = new EducOfferedSchedule(); 
                    $insert->offered_course_id = $id;
                    $insert->time_from = $time_from;
                    $insert->time_to = $time_to;
                    $insert->type = $type;
                    $insert->hours = $hours;
                    $insert->minutes = $minutes;
                    $insert->updated_by = $updated_by;
                    $insert->save(); 
                    $schedule_id = $insert->id;
                }
                $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                foreach($request->days as $day){
                    $day_letter = $this->daysLetter($day);
                    $insert = new EducOfferedScheduleDay(); 
                    $insert->offered_schedule_id = $schedule_id;
                    $insert->day = $day_letter;
                    $insert->no = $day;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }else{
                if($request->time=='TBA' || $request->days==''){
                    $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                    $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                    $schedule_id = 'new';
                }
                
            }
        }
        return $schedule_id;
    }
    private function daysLetter($day){
        $day_letter = 'error';
        if($day==1){
            $day_letter = 'M';
        }elseif($day==2){
            $day_letter = 'T';
        }elseif($day==3){
            $day_letter = 'W';
        }elseif($day==4){
            $day_letter = 'TH';
        }elseif($day==5){
            $day_letter = 'F';
        }elseif($day==6){
            $day_letter = 'S';
        }elseif($day==7){
            $day_letter = 'SU';
        }
        return $day_letter;
    }
}