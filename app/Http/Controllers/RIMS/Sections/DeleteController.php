<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function scheduleRemove(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $list_x = array();
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                $sched = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from','ASC')->first();
                if($sched!=NULL){
                    $schedule_id = $sched->id;
                    $schedDay = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->first();
                    if($schedDay!=NULL){
                        $datas['sched'] = $schedDay;
                        $datas['schedule_id'] = $schedule_id;
                        $list_x = $this->list_x($datas);
                    }
                }
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        $response = array('result' => $result,
                          'sched_id' => $schedule_id,
                          'list_x' => $list_x);
        return response()->json($response);
    }
    public function scheduleRemoveDay(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $day = $request->d;
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $list_x = array();
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            try{
                $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)
                                ->where('no',$day)->delete();
                $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                $check = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->first();
                if($check==NULL){
                    $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                }
                $sched = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->first();
                if($sched!=NULL){
                    $datas['sched'] = $sched;
                    $datas['schedule_id'] = $schedule_id;
                    $list_x = $this->list_x($datas);
                }
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        $response = array('result' => $result,
                          'sched_id' => $schedule_id,
                          'list_x' => $list_x);
        return response()->json($response);
    }
    private function list_x($datas){
        $sched = $datas['sched'];
        $schedule_id = $datas['schedule_id'];
        $time_from = $sched->schedule->course->curriculum->offered_program->school_year->time_from;
                    $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($sched->schedule->course->curriculum->offered_program->school_year->time_to)));
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
        return $list_x;
    }
}