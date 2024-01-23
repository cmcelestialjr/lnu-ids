<?php

namespace App\Http\Controllers\RIMS\Schedule;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\EducRoom;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
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
        $room_id = $request->room;        
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

        $data = $this->roomData($id,$room_id,$schedule_id,$course);

        // Return the view for program editing with the data
        return view('rims/schedule/roomViewModal', $data);
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
        $room_id = $request->room;        
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

        $data = $this->roomData($id,$room_id,$schedule_id,$course);

        // Return the view for program editing with the data
        return view('rims/schedule/roomTable', $data);
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

    private function roomData($id,$room_id,$schedule_id,$course){
        $course_schedule = 'TBA';
        $course_room = 'TBA';
        $course_room_ids = [];
        if(count($course->schedule)>0){
            foreach($course->schedule as $row){
                $course_room_ids[] = $row->room_id;
                $days = array();
                foreach($row->days as $day){
                    $days[] = $day->day;
                }
                $days1 = implode('',$days);
                $course_schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
                if($row->room_id==NULL){
                    $course_rooms[] = 'TBA';
                }else{
                    $course_rooms[] = $row->room->name;
                }
            }
            $course_schedule = implode('<br>',$course_schedules);
            $course_room = implode('<br>',$course_rooms);
        }

        $section_code = $course->section_code;
        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $time_from = $course->curriculum->offered_program->school_year->time_from;
        $time_to = date('H:i:s',strtotime('+15 minutes',strtotime($course->curriculum->offered_program->school_year->time_to)));
        $start = new DateTime($time_from);
        $end = new DateTime($time_to);
        $interval = DateInterval::createFromDateString('15 minutes');
        $time_period = new DatePeriod($start, $interval, $end);
        
        $rooms = EducRoom::get();
        $room = EducRoom::with('rooms.course',
                  'rooms.days')
            ->where('id',$room_id)
            ->whereHas('rooms.course.curriculum.offered_program', function($query) use ($school_year_id){
                $query->where('school_year_id', $school_year_id);
            })->first();
        
        $current_schedule = EducOfferedSchedule::with('days')
            ->where('id',$schedule_id)->first();
        $room_course_conflict = EducOfferedSchedule::with('days','course')
                                            ->whereHas('course', function ($subQuery) use ($school_year_id,$section_code) {
                                                $subQuery->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year_id) {
                                                    $subQuery->where('school_year_id', $school_year_id);
                                                });
                                                
                                            })
                                            ->where('room_id',$room_id)                                            
                                            ->where(function ($subquery) use ($current_schedule) {
                                                if($current_schedule){
                                                    $subquery->where(function ($subquery) use ($current_schedule) {
                                                        $subquery->orWhere(function ($innerSubquery) use ($current_schedule) {
                                                            $innerSubquery->where('time_from', '<', $current_schedule->time_to)
                                                                ->where('time_to', '>', $current_schedule->time_from);
                                                        });
                                                        $subquery->orWhere(function ($subquery) use ($current_schedule) {
                                                            $subquery->where('time_from', '<=', $current_schedule->time_from)
                                                                ->where('time_to', '>', $current_schedule->time_from);
                                                        });
                                                        $subquery->orWhere(function ($subquery) use ($current_schedule) {
                                                            $subquery->where('time_from', '<', $current_schedule->time_to)
                                                                ->where('time_to', '>=', $current_schedule->time_to);
                                                        });
                                                        $subquery->orWhere(function ($subquery) use ($current_schedule) {
                                                            $subquery->where('time_from', '>=', $current_schedule->time_from)
                                                                ->where('time_to', '<=', $current_schedule->time_from);
                                                        });
                                                    });
                                                    $schedule_days = [];
                                                    if(count($current_schedule->days)>0){
                                                        foreach($current_schedule->days as $day){
                                                            $schedule_days[] = $day->day;
                                                        }
                                                    }
                                                    $subquery->whereHas('days', function ($query) use ($schedule_days) {
                                                        $query->whereIn('day', $schedule_days);
                                                    });
                                                    $subquery->where('room_id','<>',$current_schedule->room_id);
                                                }else{
                                                    $subquery->where('room_id',0);
                                                }
                                            })
                                            // ->where(function ($subquery) {
                                            //     $subquery->where(function ($subquery) {
                                            //         $subquery->where('time_from', '>=', DB::raw('educ__offered_schedule.time_from'))
                                            //             ->where('time_to', '<=', DB::raw('educ__offered_schedule.time_from'));
                                            //     });
                                            //     $subquery->orWhere(function ($subquery) {
                                            //         $subquery->where('time_from', '<=', DB::raw('educ__offered_schedule.time_from'))
                                            //             ->where('time_to', '>', DB::raw('educ__offered_schedule.time_from'));
                                            //     });
                                            //     $subquery->orWhere(function ($subquery) {
                                            //         $subquery->where('time_from', '<', DB::raw('educ__offered_schedule.time_to'))
                                            //             ->where('time_to', '>=', DB::raw('educ__offered_schedule.time_to'));
                                            //     });
                                            //     $subquery->orWhere(function ($subquery) {
                                            //         $subquery->where('time_from', '>=', DB::raw('educ__offered_schedule.time_from'))
                                            //             ->where('time_to', '<=', DB::raw('educ__offered_schedule.time_from'));
                                            //     });
                                            // })
                                           
                                            ->get();
        // Return data for the view
        return array(
            'id' => $id,
            'course' => $course,
            'course_schedule' => $course_schedule,
            'course_room' => $course_room,
            'room' => $room,
            'rooms' => $rooms,
            'room_id' => $room_id,
            'time_period' => $time_period,
            'room_course_conflict' => $room_course_conflict
        );
        
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
            'id' => 'nullable|numeric'
        ];

        $customMessages = [
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
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'room' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'room.required' => 'Room is required.'
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
