<?php

namespace App\Http\Controllers\RIMS\Schedule;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\Users;
use App\Services\NameServices;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $instructor_id = $request->instructor;
        $schedule_id = $request->schedule_id;

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year',
                                           'course',
                                           'schedule.days')
            ->where('id',$id)
            ->first();

        if($course==NULL){
            return view('layouts/error/404');
        }

        $data = $this->data($id,$instructor_id,$schedule_id,$course);

        // Return the view for program editing with the data
        return view('rims/schedule/instructorViewModal', $data);
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
        //Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $instructor_id = $request->instructor;
        $schedule_id = $request->schedule_id;

        $course = EducOfferedCourses::with('curriculum.offered_program.school_year',
                                           'course',
                                           'schedule.room',
                                           'schedule.days')
            ->where('id',$id)
            ->first();

        if($course==NULL){
            return view('layouts/error/404');
        }

        $data = $this->data($id,$instructor_id,$schedule_id,$course);

        // Return the view for program editing with the data
        return view('rims/schedule/instructorTable', $data);
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

    private function data($id,$instructor_id,$schedule_id,$course){
        $name_services = new NameServices;

        $course_schedule = 'TBA';
        $course_instructor = 'TBA';
        $instructor_name = 'TBA';
        
        if(count($course->schedule)>0){
            foreach($course->schedule as $row){
                $days = array();
                foreach($row->days as $day){
                    $days[] = $day->day;
                }
                $days1 = implode('',$days);
                $course_schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
            }
            $course_schedule = implode('<br>',$course_schedules);
        }

        if($course->instructor_id!=NULL){
            $course_instructor = $name_services->lastname($course->instructor->lastname,$course->instructor->firstname,$course->instructor->middlename,$course->instructor->extname);
        }

        $section_code = $course->section_code;
        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));
        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);
        
        $instructor_schedule = EducOfferedSchedule::with('days','course.course')
            ->whereHas('course', function($subQuery) use ($school_year_id,$instructor_id){
                $subQuery->where('instructor_id', $instructor_id);
                $subQuery->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                    $subQuery->where('school_year_id', $school_year_id);
                });
            })->get();
        
        $instructor_select = Users::find($instructor_id);
        if($instructor_select){
            $instructor_name = $name_services->lastname($instructor_select->lastname,$instructor_select->firstname,$instructor_select->middlename,$instructor_select->extname);
        }      

        $current_schedule = EducOfferedSchedule::with('days','course')
            ->where('id',$schedule_id)->first();

        $schedule_conflict = EducOfferedSchedule::with('days','course.course')
                                            ->whereHas('course', function ($subQuery) use ($school_year_id,$instructor_id,$current_schedule) {
                                                $subQuery->where('instructor_id', $instructor_id);
                                                if($current_schedule){ 
                                                    $subQuery->where('instructor_id','<>',$current_schedule->course->instructor_id);
                                                }else{
                                                    $subQuery->where('instructor_id',0);
                                                }
                                                $subQuery->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                                    $subQuery->where('school_year_id', $school_year_id);
                                                });                                                
                                            })
                                            ->where(function ($subQuery) use ($current_schedule) {
                                                if($current_schedule){                                                    
                                                    $subQuery->where(function ($subQuery) use ($current_schedule) {
                                                        $subQuery->orWhere(function ($innerSubquery) use ($current_schedule) {
                                                            $innerSubquery->where('time_from', '<', $current_schedule->time_to)
                                                                ->where('time_to', '>', $current_schedule->time_from);
                                                        });
                                                        $subQuery->orWhere(function ($subQuery) use ($current_schedule) {
                                                            $subQuery->where('time_from', '<=', $current_schedule->time_from)
                                                                ->where('time_to', '>', $current_schedule->time_from);
                                                        });
                                                        $subQuery->orWhere(function ($subQuery) use ($current_schedule) {
                                                            $subQuery->where('time_from', '<', $current_schedule->time_to)
                                                                ->where('time_to', '>=', $current_schedule->time_to);
                                                        });
                                                        $subQuery->orWhere(function ($subQuery) use ($current_schedule) {
                                                            $subQuery->where('time_from', '>=', $current_schedule->time_from)
                                                                ->where('time_to', '<=', $current_schedule->time_from);
                                                        });
                                                    });
                                                    $schedule_days = [];
                                                    if(count($current_schedule->days)>0){
                                                        foreach($current_schedule->days as $day){
                                                            $schedule_days[] = $day->day;
                                                        }
                                                    }
                                                    $subQuery->whereHas('days', function ($query) use ($schedule_days) {
                                                        $query->whereIn('day', $schedule_days);
                                                    });                                                    
                                                }
                                            })
                                            ->get();

        // Return data for the view
        return array(
            'id' => $id,
            'course' => $course,
            'course_schedule' => $course_schedule,
            'course_instructor' => $course_instructor,
            'instructor_schedule' => $instructor_schedule,
            'time_period' => $time_period,
            'schedule_conflict' => $schedule_conflict,
            'instructor_id' => $instructor_id,
            'instructor_name' => $instructor_name,
        );
        
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'instructor' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'instructor.required' => 'Room is required.'
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
