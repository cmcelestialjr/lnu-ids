<?php

namespace App\Http\Controllers\SIMS\Courses;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\StudentsProgram;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;

        $user = Auth::user();
        $user_id = $user->id;

        $school_year_id = $request->school_year;

        $program = StudentsProgram::with('program_info','program_level','courses.course')
            ->where('user_id',$user_id)
            ->whereHas('courses',function($query) use ($school_year_id,$user_id){
                $query->where('user_id',$user_id);
                $query->where('school_year_id',$school_year_id);
            })->first();      

        // Check if validation fails
        if ($program==NULL) {
            return view('layouts/error/404');
        }

        $coursesWoSched = $this->getCourseSched($school_year_id,$user_id,$name_services,'wo');
        $coursesWSched = $this->getCourseSched($school_year_id,$user_id,$name_services,'w');
        
        $schedule = EducOfferedSchedule::whereHas('course',function($query) use ($school_year_id,$user_id){
                $query->whereHas('students',function($query) use ($school_year_id,$user_id){
                    $query->where('user_id',$user_id);
                    $query->where('school_year_id',$school_year_id);
                });
            })->select('time_from')
            ->orderBy('time_from','ASC')
            ->groupBy('time_from')->get();

        $schedules = [];
        if($schedule->count()>0){
            foreach($schedule as $row){
                $time_from = $row->time_from;
                $d[0] = '';
                $d[1] = '';
                $d[2] = '';
                $d[3] = '';
                $d[4] = '';
                $d[5] = '';
                $d[6] = '';
                
                if(count($coursesWSched)>0){
                    foreach($coursesWSched as $subRow){
                        if (in_array($time_from, $subRow['time_from'])){
                            for($i = 0; $i<7 ; $i++){
                                if (in_array($i, $subRow['days_list'])){
                                    $d[$i] = '<label>'.$subRow['course_code'].'</label><br>'.
                                             $subRow['instructor'].'<br>'.
                                             $subRow['time'].'-'.$subRow['room'].'<br>';
                                }
                            }                            
                        }
                    }
                }
                

                $data_list['time'] = date('h:ia',strtotime($time_from));
                $data_list['0'] = $d[0];
                $data_list['1'] = $d[1];
                $data_list['2'] = $d[2];
                $data_list['3'] = $d[3];
                $data_list['4'] = $d[4];
                $data_list['5'] = $d[5];
                $data_list['6'] = $d[6];

                array_push($schedules,$data_list);
            }
        }
        
        
        $data = array(
            'program' => $program,
            'schedules' => $schedules,
            'coursesWoSched' => $coursesWoSched
        );
        return view('sims/courses/scheduleTable',$data);
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
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getCourseSched($school_year_id,$user_id,$name_services,$option){
        $query = EducOfferedCourses::with('schedule.days',
                                            'schedule.room',
                                            'instructor',
                                            'course.grade_level');
        if($option=='wo'){
            $query = $query->doesntHave('schedule');
        }else{
            $query = $query->whereHas('schedule');
        }
        $query = $query->whereHas('students',function($query) use ($school_year_id,$user_id){
                $query->where('user_id',$user_id);
                $query->where('school_year_id',$school_year_id);
            })->get()
            ->map(function($query) use ($name_services) {
                $time_from = [];
                $days_list = [];
                $schedule = '<u>TBA</u>';
                $time = '<u>TBA</u>';
                $room = '<u>TBA</u>';
                $instructor = 'TBA';
                if($query->instructor_id!=NULL){
                    $instructor = $name_services->lastname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                }
                if(count($query->schedule)>0){
                    foreach($query->schedule as $row){
                        $days = array();
                        foreach($row->days as $day){
                            $days[] = $day->day;
                            $days_list[] = $day->no;
                        }
                        $days1 = implode('',$days);
                        $time_from[] = $row->time_from;
                        $schedules[] = '<u>'.date('h:ia',strtotime($row->time_from)).'-'.
                                            date('h:ia',strtotime($row->time_to)).$days1.'</u>';
                        $times[] = '<u>'.date('h:ia',strtotime($row->time_from)).'-'.
                                            date('h:ia',strtotime($row->time_to)).'</u>';
                        if($row->room_id==NULL){
                            $rooms[] = '<u>TBA</u>';
                        }else{
                            $rooms[] = '<u>'.$row->room->name.'</u>';
                        }
                    }
                    $schedule = implode('<br>',$schedules);
                    $time = implode('<br>',$times);
                    $room = implode('<br>',$rooms);
                }
                return [
                    'id' => $query->id,
                    'section_code' => $query->section_code,
                    'course_code' => $query->code,
                    'time_from' => $time_from,
                    'days_list' => $days_list,
                    'schedule' => $schedule,
                    'time' => $time,
                    'room' => $room,
                    'instructor' => $instructor,
                    'grade_level' => $query->course->grade_level->name
                ];
            })->toArray();
        return $query;
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
            'school_year' => 'required|numeric'
        ];

        $customMessages = [
            'school_year.required' => 'School Year is required.',
            'school_year.numeric' => 'School Year must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
