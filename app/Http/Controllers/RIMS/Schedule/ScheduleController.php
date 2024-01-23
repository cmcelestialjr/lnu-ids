<?php

namespace App\Http\Controllers\RIMS\Schedule;

use App\Http\Controllers\Controller;
use App\Models\EducDay;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\EducRoom;
use App\Models\UsersRoleList;
use App\Services\NameServices;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;

        $id = $request->id;       

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year',
                                        'instructor',
                                        'schedule.days',
                                        'schedule.room',
                                        'course')
            ->where('id',$id)->first();

        if ($course==NULL) {
            return view('layouts/error/404');
        }

        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));
        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);        

        $no_students = 0;
        $schedule = 'TBA';
        $room = 'TBA';
        $instructor_id = 'TBA';
        $instructor_name = 'TBA';
        $room_id = 'TBA';
        $room_name = 'TBA';
        $hours_list = array(0,1,2,3,4,5,6);
        $minutes_list = array(0,15,30,45);

        if($course->students){
            $no_students = count($course->students);
        }

        if($course->instructor_id!=NULL){
            $instructor_id = $course->instructor_id;
            $instructor_name = $name_services->lastname($course->instructor->lastname,$course->instructor->firstname,$course->instructor->middlename,$course->instructor->extname);
        }

        if(count($course->schedule)>0){
            $x = 0;
            foreach($course->schedule as $row){    
                $days = array();
                if($x==0){
                    $room_id = $row->room_id;
                    $room_name = $row->room->name;
                }
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
                $x++;
            }
            $schedule = implode('<br>',$schedules);
            $room = implode('<br>',$rooms);
        }
        
        $data = array(
            'id' => $id, 
            'course' => $course,          
            'time_period' => $time_period,
            'schedule' => $schedule,
            'room' => $room,
            'instructor_id' => $instructor_id,
            'instructor_name' => $instructor_name,
            'room_id' => $room_id,
            'room_name' => $room_name,
            'no_students' => $no_students,
            'hours_list' => $hours_list,
            'minutes_list' => $minutes_list
        );
        return view('rims/schedule/scheduleCourseModal',$data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $data = array();
        $id = $request->id;
        $schedule_id = $request->schedule_id;

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year')->where('id',$id)->first();

        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $instructor_id = $course->instructor_id;
        $section_code = $course->section_code;
        $offered_curriculum_id = $course->offered_curriculum_id;
        $year_level = $course->year_level;
        $hours = $course->hours;
        $minutes = $course->minutes;
        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));

        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);

        $room_id = NULL;
        $course_room = NULL;
        $course_instructor = NULL;
        $room_schedule_conflict = NULL;
        $instructor_schedule_conflict = NULL;
        $scheduleRemoveDayTr = 'hide';

        $course_schedule = EducOfferedSchedule::with('days','course.course')
            ->where('id',$schedule_id)
            ->orderBy('time_from','ASC')->first(); 
        $course_schedule_others = EducOfferedSchedule::with('days','course.course')
            ->where('offered_course_id',$id)
            ->where('id','<>',$schedule_id)
            ->orderBy('time_from','ASC')->get();
        $course_section = EducOfferedCourses::with('schedule.days','course')
            ->where('offered_curriculum_id',$offered_curriculum_id)
            ->where('year_level',$year_level)
            ->where('section_code',$section_code)
            ->where('id','<>',$id)
            ->get();
            
        if($course_schedule){
            $room_id = $course_schedule->room_id;
            $hours = $course_schedule->hours;
            $minutes = $course_schedule->minutes;
            foreach ($course_schedule->days as $day) {
                $schedule_days[] = $day->day;
            }
            $datas['id'] = $id;
            $datas['school_year_id'] = $school_year_id;
            $datas['schedule_id'] = $schedule_id;
            $datas['room_id'] = $room_id;
            $datas['schedule'] = $course_schedule;
            $datas['schedule_days'] = $schedule_days;
            $datas['instructor_id'] = $instructor_id;
            if($room_id){
                $room_schedule_conflict = $this->room_schedule_conflict($datas);
            }
            if($instructor_id){
                $instructor_schedule_conflict = $this->instructor_schedule_conflict($datas);
            }
            $scheduleRemoveDayTr = '';
        }
        if($room_id){
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
        if($instructor_id){
            $course_instructor = EducOfferedCourses::with('schedule.days','course')
                                ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                    $subQuery->where('school_year_id', $school_year_id);
                                })
                                ->where('section_code','<>',$section_code)
                                ->where('instructor_id',$instructor_id)
                                ->get();
        }
        
        $data = array(           
            'time_period' => $time_period,
            'course_section' => $course_section,
            'course_schedule' => $course_schedule,
            'course_schedule_others' => $course_schedule_others,
            'course_room' => $course_room,
            'course_instructor' => $course_instructor,
            'room_schedule_conflict' => $room_schedule_conflict,
            'instructor_schedule_conflict' => $instructor_schedule_conflict,            
            'scheduleRemoveDayTr' => $scheduleRemoveDayTr,
            'hours' => $hours,
            'minutes' => $minutes
        );
        return view('rims/schedule/scheduleCourseTable',$data);
    }

    /**
     * Display the specified resource.
     */
    public function reShow(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $data = array();
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $instructor_id = ($request->instructor_id=='TBA') ? NULL : $request->instructor_id;
        $room_id = ($request->room_id=='TBA') ? NULL : $request->room_id;
        
        $course = EducOfferedCourses::with('curriculum.offered_program.school_year')->where('id',$id)->first();
        $school_year_id = $course->curriculum->offered_program->school_year_id;        
        $section_code = $course->section_code;
        $offered_curriculum_id = $course->offered_curriculum_id;
        $year_level = $course->year_level;
        $hours = $course->hours;
        $minutes = $course->minutes;
        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));

        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);
        
        $course_room = NULL;
        $course_instructor = NULL;
        $room_schedule_conflict = NULL;
        $instructor_schedule_conflict = NULL;
        $scheduleRemoveDayTr = 'hide';

        $course_schedule = EducOfferedSchedule::with('days','course.course')
            ->where('id',$schedule_id)
            ->orderBy('time_from','ASC')->first(); 
        $course_schedule_others = EducOfferedSchedule::with('days','course.course')
            ->where('offered_course_id',$id)
            ->where('id','<>',$schedule_id)
            ->orderBy('time_from','ASC')->get();
        $course_section = EducOfferedCourses::with('schedule.days','course')
            ->where('offered_curriculum_id',$offered_curriculum_id)
            ->where('year_level',$year_level)
            ->where('section_code',$section_code)
            ->where('id','<>',$id)
            ->get();
            
        if($course_schedule){
            $hours = $course_schedule->hours;
            $minutes = $course_schedule->minutes;
            foreach ($course_schedule->days as $day) {
                $schedule_days[] = $day->day;
            }
            $datas['id'] = $id;
            $datas['school_year_id'] = $school_year_id;
            $datas['schedule_id'] = $schedule_id;
            $datas['room_id'] = $room_id;
            $datas['schedule'] = $course_schedule;
            $datas['schedule_days'] = $schedule_days;
            $datas['instructor_id'] = $instructor_id;
            if($room_id){
                $room_schedule_conflict = $this->room_schedule_conflict($datas);
            }
            if($instructor_id){
                $instructor_schedule_conflict = $this->instructor_schedule_conflict($datas);
            }
            $scheduleRemoveDayTr = '';
        }
        if($room_id){
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
        if($instructor_id){
            $course_instructor = EducOfferedCourses::with('schedule.days','course')
                                ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                    $subQuery->where('school_year_id', $school_year_id);
                                })
                                ->where('section_code','<>',$section_code)
                                ->where('instructor_id',$instructor_id)
                                ->get();
        }
        
        $data = array(           
            'time_period' => $time_period,
            'course_section' => $course_section,
            'course_schedule' => $course_schedule,
            'course_schedule_others' => $course_schedule_others,
            'course_room' => $course_room,
            'course_instructor' => $course_instructor,
            'room_schedule_conflict' => $room_schedule_conflict,
            'instructor_schedule_conflict' => $instructor_schedule_conflict,            
            'scheduleRemoveDayTr' => $scheduleRemoveDayTr,
            'hours' => $hours,
            'minutes' => $minutes
        );
        return view('rims/schedule/scheduleCourseTable',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $result = 'error';
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $room_id = ($request->room_id=='TBA') ? NULL : $request->room_id;
        $instructor_id = ($request->instructor_id=='TBA') ? NULL : $request->instructor_id;
        $select_hours = $request->select_hours;
        $select_minutes = $request->select_minutes;
        $select_days = $request->select_days;
        $select_time = $request->select_time;
        $select_type = $request->select_type;

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year')->where('id',$id)->first();

        
        if($course==NULL){
            return response()->json(['result' => $result]);
        }
        
        // Start a database transaction
       // DB::beginTransaction();  
        try {
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;
            
            if($select_time=='TBA'){
                if($schedule_id!='New'){
                    $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                    $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                }
            }else{
                $time = explode('-',$select_time);
                $time_from = date('H:i:00',strtotime($time[0]));
                $time_to = date('H:i:00',strtotime($time[1]));
                $school_year_id = $course->curriculum->offered_program->school_year_id;
                $section_code = $course->section_code;

                if($schedule_id=='New'){
                    $insert = new EducOfferedSchedule();
                    $insert->offered_course_id = $id;
                    $insert->room_id = $room_id;
                    $insert->time_from = $time_from;
                    $insert->time_to = $time_to;
                    $insert->type = $select_type;
                    $insert->hours = $select_hours;
                    $insert->minutes = $select_minutes;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $schedule_id = $insert->id;
                }
                
                if($schedule_id!='New'){
                    $schedule_days = [];
                    $schedule_days_no = [];
                    foreach($select_days as $day){
                        $educDay = EducDay::where('no',$day)->first();
                        if($educDay){                        
                            $schedule_days[] = $educDay->name;
                            $schedule_days_no[] = $educDay->no;
                        }
                    }
                    $datas['id'] = $id;
                    $datas['school_year_id'] = $school_year_id;
                    $datas['schedule_id'] = $schedule_id;
                    $datas['schedule_days'] = $schedule_days;
                    $datas['section_code'] = $section_code;
                    $datas['time_from'] = date('H:i:00',strtotime($time_from));
                    $datas['time_to'] = date('H:i:00',strtotime($time_to));

                    $schedule_course_conflict = $this->schedule_course_conflict($datas);
                    if($schedule_course_conflict==NULL){
                        $delete = EducOfferedScheduleDay::where('offered_schedule_id',$schedule_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE educ__offered_schedule_day AUTO_INCREMENT = 0;");
                        for($i=0; $i<count($schedule_days); $i++){
                            $insert = new EducOfferedScheduleDay();
                            $insert->offered_schedule_id = $schedule_id;
                            $insert->day = $schedule_days[$i];
                            $insert->no = $schedule_days_no[$i];
                            $insert->updated_by = $updated_by;
                            $insert->save();
                        }
                        EducOfferedSchedule::where('id', $schedule_id)
                                ->update(['time_from' => $time_from,
                                        'time_to' => $time_to,
                                        'type' => $select_type,
                                        'room_id' => $room_id,
                                        'hours' => $select_hours,
                                        'minutes' => $select_minutes,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        ]);                    
                    
                        $course_schedule = EducOfferedSchedule::with('days')->where('id',$schedule_id)->first();

                        $datas['room_id'] = $room_id;
                        $datas['schedule'] = $course_schedule;                
                        $datas['instructor_id'] = $instructor_id;                

                        if($room_id){
                            $room_schedule_conflict = $this->room_schedule_conflict($datas);
                            if($room_schedule_conflict->count()>0){
                                EducOfferedSchedule::where('id', $schedule_id)
                                    ->update(['room_id' => NULL]);
                            }
                        }

                        if($instructor_id){
                            $instructor_schedule_conflict = $this->instructor_schedule_conflict($datas);
                            if($instructor_schedule_conflict->count()>0){
                                $instructor_id = NULL;
                            }
                        }
                    }else{
                        $delete = EducOfferedSchedule::where('id',$schedule_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE educ__offered_schedule AUTO_INCREMENT = 0;");
                        //DB::commit();
                        $response = array('result' => 'Conflict Schedule');
                        return response()->json($response); 
                    }
                }
            }
            EducOfferedCourses::where('id', $id)
                ->update(['instructor_id' => $instructor_id,
                         'updated_by' => $updated_by,
                         'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            $labels = $this->labels($id);
            //DB::commit();
            $result = 'success';
            // Prepare a JSON response with the result
            $response = array('result' => $result,
                              'schedule_id' => $schedule_id,
                              'schedule_time' => $select_time,
                              'labels' => $labels);
            return response()->json($response);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $list_x = array();
        $labels = array();
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
                $schedule = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from','ASC')->first();
                if($schedule){
                    $schedule_id = $schedule->id;
                }else{
                    $schedule_id = 'New';
                }
                $labels = $this->labels($id);
                $result = 'success';
            } catch (QueryException $e) {
                // Handle database query exceptions
                return $this->handleDatabaseError($e);
            } catch (PDOException $e) {
                // Handle PDO exceptions
                return $this->handleDatabaseError($e);
            } catch (Exception $e) {
                // Handle other exceptions
                return $this->handleOtherError($e);
            }
        }
        $response = array('result' => $result,
                          'schedule_id' => $schedule_id,
                          'list_x' => $list_x,
                          'labels' => $labels);
        return response()->json($response);
    }
    public function destroyDay(Request $request){
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        $day = $request->d;
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $list_x = array();
        $labels = array();
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
                $schedule = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from','ASC')->first();
                if($schedule){
                    $schedule_id = $schedule->id;
                }else{
                    $schedule_id = 'New';
                }
                $labels = $this->labels($id);
                $result = 'success';
            } catch (QueryException $e) {
                // Handle database query exceptions
                return $this->handleDatabaseError($e);
            } catch (PDOException $e) {
                // Handle PDO exceptions
                return $this->handleDatabaseError($e);
            } catch (Exception $e) {
                // Handle other exceptions
                return $this->handleOtherError($e);
            }
        }
        $response = array('result' => $result,
                          'schedule_id' => $schedule_id,
                          'list_x' => $list_x,
                          'labels' => $labels);
        return response()->json($response);
    }
    private function labels($id){
        $name_services = new NameServices;
        $course = EducOfferedCourses::with(
                                    'instructor',
                                    'schedule.days',
                                    'schedule.room',
                                    'course')
            ->where('id',$id)->first();

        $scheduleLabel = 'TBA';
        $roomLabel = 'TBA';
        $instructorLabel = 'TBA';

        if($course->instructor_id!=NULL){
            $instructorLabel = $name_services->lastname($course->instructor->lastname,$course->instructor->firstname,$course->instructor->middlename,$course->instructor->extname);
        }

        if(count($course->schedule)>0){
            $x = 0;
            foreach($course->schedule as $row){    
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
                $x++;
            }
            $scheduleLabel = implode('<br>',$schedules);
            $roomLabel = implode('<br>',$rooms);
        }   
        return array('id' => $id,
                'scheduleLabel' => $scheduleLabel,
                'roomLabel' => $roomLabel,
                'instructorLabel' => $instructorLabel);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function time(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $id = $request->id; 
        $x = 0;               
        $schedule_id = 'New';
        $schedules = array();
        $result = 'success';

        $schedule = EducOfferedSchedule::where('offered_course_id',$id)->orderBy('time_from')->get();        
        if($schedule->count()>0){
            foreach($schedule as $row){
                $time_from = date('h:ia',strtotime($row->time_from));
                $time_to = date('h:ia',strtotime($row->time_to));
                if($x==0){
                    $schedule_id = $row->id;
                }
                $data['id'] = $row->id;
                $data['text'] = $time_from.'-'.$time_to;
                array_push($schedules,$data);
                $x++;
            }
        }

        // Prepare a JSON response with the result
        $response = array('result' => $result,
                          'schedule_id' => $schedule_id,
                          'schedules' => $schedules);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function details(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idStringValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $id = $request->id;
        $result = 'success';
        $days = array();
        $time = NULL;
        $type = 'Lec';
        $hours = NULL;
        $minutes = NULL;

        $schedule = EducOfferedSchedule::with('days')->where('id',$id)->orderBy('time_from')->first();
               
        if($schedule){
            $time = date('h:ia',strtotime($schedule->time_from)).'-'.date('h:ia',strtotime($schedule->time_to));
            $type = $schedule->type;
            $hours = $schedule->hours;
            $minutes = $schedule->minutes;
            foreach($schedule->days as $row){
                $data['id'] = $row->no;
                $data['text'] = $row->day;
                array_push($days,$data);
            }
        }       

        // Prepare a JSON response with the result
        $response = array('result' => $result,
                          'days' => $days,
                          'time' => $time,
                          'type' => $type,
                          'hours' => $hours,
                          'minutes' => $minutes);
        return response()->json($response);
    }

    public function courseSchedRmInstructor(Request $request){
        $name_services = new NameServices;
        $id = $request->id;
        $schedule_id = $request->schedule_id;
        if($schedule_id==NULL){
            $schedule = EducOfferedSchedule::select('id')->where('offered_course_id',$id)->orderBy('time_from')->first();
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
        $instructors = UsersRoleList::with('user')->where('role_id',3)->where('user_id',$query->instructor_id)->first();
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
    private function schedule_course_conflict($datas){
        $school_year_id = $datas['school_year_id'];
        $schedule_id = $datas['schedule_id'];
        $time_from = $datas['time_from'];
        $time_to = $datas['time_to'];
        $schedule_days = $datas['schedule_days'];
        $section_code = $datas['section_code'];
        $schedule_check = EducOfferedSchedule::whereHas('course', function ($subQuery) use ($school_year_id,$section_code) {
                                                $subQuery->where('section_code',$section_code);
                                                $subQuery->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                                    $subQuery->where('school_year_id', $school_year_id);
                                                });
                                            })
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
        return $schedule_check;
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
    private function days_list($days_sched){
        $days_array = array('SU','M','T','W','TH','F','S');
        return $days_array;
    }
    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idStringValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'schedule_id' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'schedule_id.required' => 'Schedule is required.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function updateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'schedule_id' => 'required|string',
            'room_id' => 'required|string',
            'instructor_id' => 'required|string',
            'select_hours' => 'required|numeric',
            'select_minutes' => 'required|numeric',
            'select_days' => 'nullable|array',
            'select_time' => 'required|string',
            'select_type' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'schedule_id.required' => 'Schedule is required.',
            'room_id.required' => 'Room is required.',
            'instructor_id.required' => 'Instructor is required.',
            'select_hours.required' => 'Hours is required.',
            'select_minutes.required' => 'Minute is required.',
            'select_time.required' => 'Time is required.',
            'select_type.required' => 'Type is required.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
    /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
