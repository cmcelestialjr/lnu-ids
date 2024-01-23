<?php

namespace App\Http\Controllers\FIS;

use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }
        
        $user = Auth::user();
        $instructor_id = $user->id;
        $level = $request->level;

        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $query = EducCourses::
            whereHas('courses', function ($query) use ($instructor_id,$level) {
                $query->where('instructor_id',$instructor_id);
            })
            ->whereHas('grade_level', function ($query) use ($level) {
                if($level==NULL){
                    $query->where('program_level_id','>',0);
                }else{
                    $query->whereIn('program_level_id',$level);
                }
            })
            ->groupBy('code','name')
            ->get() 
            ->map(function($query) use ($instructor_id) {
                $code = $query->code;
                $name = $query->name;
                $count = EducOfferedCourses::
                    where('instructor_id',$instructor_id)
                    ->whereHas('course',function ($query) use ($code,$name){
                        $query->where('code',$code);
                        $query->where('name',$name);
                    })->count();
                return [
                    'id' => $query->id,
                    'code' => $code,
                    'name' => $name,
                    'count' => $count
                ];
            })->toArray();
        
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['code'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['count'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan btn-sm courseViewModal"
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
     * Display a listing of the resource.
     */
    public function semTable(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->semTableValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $data = array();
        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year = $request->school_year;
        $level = $request->level;

        $query = EducOfferedCourses::with('course',
                                         'schedule.days',
                                         'curriculum.offered_program',
                                         'curriculum.curriculum',
                                         'status',
                                         'students',
                                         'w_grade',
                                         'wo_grade')
            ->where('instructor_id',$instructor_id)
            ->whereHas('curriculum.offered_program', function ($query) use ($school_year) {
                $query->where('school_year_id',$school_year);
            })
            ->whereHas('course.grade_level', function ($query) use ($level) {
                if($level==NULL){
                    $query->where('program_level_id','>',0);
                }else{
                    $query->whereIn('program_level_id',$level);
                }
            })->get()
            ->map(function($query) {
                $schedule_implode = 'TBA';
                $room_implode = 'TBA';
                if(count($query->schedule)>0){
                    foreach($query->schedule as $row){
                        $days = array();
                        if($row->room_id==NULL){
                            $room = 'TBA';
                        }else{
                            $room = $row->room->name;
                        }
                        foreach($row->days as $day){
                            $days[] = $day->day;
                        }
                        $days1 = implode('',$days);
                        $rooms[] = $room;
                        $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                            date('h:ia',strtotime($row->time_to)).' '.$days1;
                    }
                    $schedule_implode = implode('<br>',$schedules);
                    $room_implode = implode('<br>',$rooms);                                                        
                }
                $no_student = '-';
                if(count($query->students)>0){
                    $no_student = count($query->students);
                }
                $w_grade = '-';
                if(count($query->w_grade)>0){
                    $w_grade = count($query->w_grade);
                }
                $wo_grade = '-';
                if(count($query->wo_grade)>0){
                    $wo_grade = count($query->wo_grade);
                }
                if($query->status_id==1){
                    $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$query->status->name.'</button>';
                }else{
                    $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$query->status->name.'</button>';
                }
                return [
                    'id' => $query->id,
                    'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                    'curriculum' => $query->curriculum->code.'-'.$query->curriculum->curriculum->year_from.'-'.$query->curriculum->curriculum->year_to,
                    'section' => $query->section_code,
                    'course_code' => $query->code,
                    'units' => $query->course->units,
                    'schedule' => $schedule_implode,
                    'room' => $room_implode,
                    'no_student' => $no_student,
                    'w_grade' => $w_grade,
                    'wo_grade' => $wo_grade,
                    'status' => $status,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['section'];
                $data_list['f5'] = $r['course_code'];
                $data_list['f6'] = $r['units'];
                $data_list['f7'] = $r['schedule'];
                $data_list['f8'] = $r['room'];
                $data_list['f9'] = $r['no_student'];
                $data_list['f10'] = $r['w_grade'];
                $data_list['f11'] = $r['wo_grade'];
                $data_list['f12'] = $r['status'];
                $data_list['f13'] = '<button class="btn btn-primary btn-primary-scan btn-sm studentsListModal"
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

        $user = Auth::user();
        $instructor_id = $user->id;
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)
            ->where('instructor_id',$instructor_id)
            ->first();

        // Check if validation fails
        if ($query==NULL) {
            return view('layouts/error/404');
        }

        $schedule_implode = 'TBA';
        $room_implode = 'TBA';
        if(count($query->schedule)>0){
            foreach($query->schedule as $row){
                $days = array();
                if($row->room_id==NULL){
                    $room = 'TBA';
                }else{
                    $room = $row->room->name;
                }
                foreach($row->days as $day){
                    $days[] = $day->day;
                }
                $days1 = implode('',$days);
                $rooms[] = $room;
                $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
            }
            $schedule_implode = implode('<br>',$schedules);
            $room_implode = implode('<br>',$rooms);                                                        
        }
        if($query->status_id==1){
            $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$query->status->name.'</button>';
        }else{
            $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$query->status->name.'</button>';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'schedule' => $schedule_implode,
            'room' => $room_implode,
            'status' => $status
        );
        return view('fis/courses/studentsListModal',$data);
    }

    /**
     * Display the specified resource.
     */
    public function showTable(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $name_services = new NameServices;
        $user = Auth::user();
        $instructor_id = $user->id;
        $id = $request->id;

        $query = StudentsInfo::with('info','program','grade_level')
            ->whereHas('courses.course', function ($query) use ($id,$instructor_id) {
                $query->where('id',$id);
                $query->where('instructor_id',$instructor_id);
            })->get()
            ->map(function($query) use ($name_services,$id) {
                $name = $name_services->lastname($query->info->lastname,
                                            $query->info->firstname,
                                            $query->info->middlename,
                                            $query->info->extname);
                $grade_query = StudentsCourses::with('status')
                    ->where('offered_course_id',$id)
                    ->where('user_id',$query->user_id)
                    ->first();
                $student_course_id = $grade_query->id;
                $student_course_status_id = $grade_query->student_course_status_id;
                if($grade_query->final_grade==NULL){
                    $grade = 'NG';
                }else{
                    $grade = $grade_query->final_grade;
                }
                if($grade_query->student_course_status_id==NULL){
                    $student_course_option = '';
                    $student_course_name = '';
                }else{
                    $student_course_option = $grade_query->status->option;
                    $student_course_name = $grade_query->status->name;
                }   
                return [
                    'id' => $id,
                    'student_course_id' => $student_course_id,
                    'user_id' => $query->user_id,
                    'name' => $name,
                    'id_no' => $query->id_no,
                    'program' => $query->program->shorten,
                    'grade_level' => $query->grade_level->name,
                    'grade' => $grade,
                    'graded_at' => $grade_query->graded_at,
                    'student_course_option' => $student_course_option,
                    'student_course_name' => $student_course_name,
                    'student_course_status_id' => $student_course_status_id
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['id_no'];
                $data_list['f4'] = $r['program'];
                $data_list['f5'] = $r['grade_level'];
                if(date('Y-m-d')>$r['graded_at'] && $r['graded_at']!=NULL){
                    $color = $this->getStatusColor($r['student_course_option']);
                    $data_list['f6'] = '<span style="color:'.$color.'">'.$r['student_course_name'].'</span>';
                    $data_list['f7'] = '<span>'.$r['grade'].'</span>';
                }else{
                    $data_list['f6'] = $this->statusSelect($r['student_course_status_id'],$r['student_course_id'],$x);
                    $data_list['f7'] = '<input type="number" class="form-control inputGrade"
                                            id="studentGrade'.$x.'"
                                            data-id="'.$r['student_course_id'].'"
                                            data-x="'.$x.'"
                                            value="'.$r['grade'].'" style="width:120px">';
                }
                
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
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
            return response()->json(['result' => 'error']);
        }
        
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $val = $request->val;
        $option = $request->option;
        $value = $request->value;

        $check = StudentsCourses::where('id',$id)
            ->whereHas('course',function($query) use ($updated_by){
                $query->where('instructor_id',$updated_by);
            })
            ->first();

        // Check if null
        if ($check==NULL) {
            return response()->json(['result' => 'error']);
        }

        DB::beginTransaction();
        try{
            if($option=='' || $option==NULL){
                $graded_by = $updated_by;
                if($val==''){
                    $val = NULL;
                    $graded_by = NULL;
                }
                StudentsCourses::where('id', $id)
                ->update(['grade' => NULL,
                    'final_grade' => NULL,
                    'student_course_status_id' => $val,
                    'graded_by' => $graded_by,
                    'graded_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }else{
                StudentsCourses::where('id', $id)
                ->update(['grade' => $val,
                    'final_grade' => $val,
                    'student_course_status_id' => $value,
                    'graded_by' => $updated_by,
                    'graded_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
            return response()->json(['result' => 'success']);

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
    public function destroy(string $id)
    {
        //
    }

    private function statusSelect($status_selected,$id,$x){
        $statuses = StudentsCourseStatus::get();

        $selectHTML = '<select class="form-control select2-table selectStatus" id="studentSelect'.$x.'" style="width:100%">';       
        $selectHTML .= '<option value="" data-id="'.$id.'" data-option="1" data-x="" data-color="">Please select</option>'; 
        foreach ($statuses as $status) {
            $color = $this->getStatusColor($status->option);
            if($status_selected==$status->id){
                $selectHTML .= '<option value="'.$status->id.'" data-id="'.$id.'" data-option="'.$status->w_grade.'" data-x="'.$x.'" data-color="'.$color.'" selected>'.$status->shorten.'-'.$status->name.'</option>';
            }else{
                $selectHTML .= '<option value="'.$status->id.'" data-id="'.$id.'" data-option="'.$status->w_grade.'" data-x="'.$x.'" data-color="'.$color.'">'.$status->shorten.'-'.$status->name.'</option>';
            }
        }
        $selectHTML .= '</select>';
        
        return $selectHTML;
    }

    private function getStatusColor($status){
        if ($status == 1) {
            return '#006400';
        } else{
            return 'red';
        }
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
            'level' => 'nullable|array',
            'level.*' => 'numeric',
        ];
        
        $customMessages = [
            'level.array' => 'Level must be an array',
            'level.*.numeric' => 'All elements in the "level" array must be numbers',
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
            'id' => 'required|numeric',
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function semTableValidateRequest(Request $request)
    {
        $rules = [
            'school_year' => 'required|numeric',
            'level' => 'nullable|array',
            'level.*' => 'numeric',
        ];
        
        $customMessages = [
            'school_year.required' => 'School Year is required',
            'school_year.numeric' => 'School Year must be numeric',
            'level.array' => 'Level must be an array',
            'level.*.numeric' => 'All elements in the "level" array must be numbers',
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
            'val' => 'nullable|numeric',
            'option' => 'nullable|string',
            'value' => 'nullable|numeric',
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
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
