<?php

namespace App\Http\Controllers\SIMS\Courses;

use App\Http\Controllers\Controller;
use App\Models\StudentsCourses;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {  
        // Initialize data array
        $data = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($data);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $status = $request->status;
        $program_level = $request->program_level;

        $query = StudentsCourses::with('course_credit','grade_period','status')        
            ->where('program_level_id',$program_level)
            ->where('user_id',$user_id);
        if($status>=1){
            $query = $query->where('student_course_status_id',$status);
        }
        $query = $query->orderBy('year_from','DESC')
            ->orderBy('grade_period_id','DESC')
            ->get()->map(function($query)  {
                $status = 'Ongoing';
                if($query->student_course_status_id){
                    $status = $query->status->name;
                }
                $grade = 'NG';
                if($query->final_grade){
                    $grade = $query->final_grade;
                }
                $course_credit = '-';
                if($query->credit_course_id){
                    $course_credit = $query->course_credit->code;
                }
                return [
                    'id' => $query->id,
                    'sy' => $query->year_from.'-'.$query->year_to.' '.$query->grade_period->name_no,
                    'code' => $query->course_code,
                    'name' => $query->course_desc,
                    'course_units' => $query->course_units,
                    'lab_units' => $query->lab_units,
                    'status' => $status,
                    'grade' => $grade,
                    'course_credit' => $course_credit
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['sy'];
                $data_list['f3'] = $r['code'];
                $data_list['f4'] = $r['name'];
                $data_list['f5'] = $r['course_units'];
                $data_list['f6'] = $r['status'];
                $data_list['f7'] = $r['grade'];
                $data_list['f8'] = $r['course_credit'];
                $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan listCourseView"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
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
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;
        $id = $request->id;
        
        $query = StudentsCourses::with('course_credit',                                    
                                    'credited_by',
                                    'course.instructor',
                                    'course.schedule.days',
                                    'course.schedule.room')
            ->find($id);

        $schedule = 'TBA';
        $instructor_name = 'TBA';
        $room_name = 'TBA';
        $course_credit_code = '';
        $course_credit_by = '';

        if($query->course->instructor_id!=NULL){
            $instructor_name = $name_services->lastname($query->course->instructor->lastname,$query->course->instructor->firstname,$query->course->instructor->middlename,$query->course->instructor->extname);
        }
        if(count($query->course->schedule)>0){
            $x = 0;
            foreach($query->course->schedule as $row){    
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
            $schedule = implode('<br>',$schedules);
            $room_name = implode('<br>',$rooms);
        }        
        if($query->credit_course_id){
            $course_credit_code = $query->course_credit->code.' - '.$query->course_credit->name;
            $course_credit_by = $name_services->lastname($query->credited_by->lastname,$query->credited_by->firstname,$query->credited_by->middlename,$query->credited_by->extname);
        }
        $course = [
                'course_code' => $query->course_code,
                'course_desc' => $query->course_desc,
                'course_units' => $query->course_units,
                'lab_units' => $query->lab_units,
                'school_year_id' => $query->school_year_id,
                'school_name' => $query->school_name,
                'program_shorten' => $query->program_shorten,
                'program_name' => $query->program_name,
                'credit_course_id' => $query->credit_course_id,
                'course_credit_code' => $course_credit_code,
                'course_credit_by' => $course_credit_by,
                'credited_at' => date('F d, Y',strtotime($query->credited_at)),
                'schedule' => $schedule,
                'instructor_name' => $instructor_name,
                'room_name' => $room_name
            ];
        $data = array(
            'id' => $id,
            'course' => $course
        );
        return view('sims/courses/listViewModal',$data);
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

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'status' => 'required|numeric',
            'program_level' => 'required|numeric'
        ];

        $customMessages = [
            'status.required' => 'Status is required.',
            'status.numeric' => 'Status must be a number.',
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
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
            'id.required' => 'Id is required.',
            'id.numeric' => 'Id must be a number.'
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
