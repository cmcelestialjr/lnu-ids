<?php

namespace App\Http\Controllers\RIMS\SchoolYear;

use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducDepartments;
use App\Models\EducDiscount;
use App\Models\EducDiscountFeesType;
use App\Models\EducDiscountList;
use App\Models\EducFeesPeriod;
use App\Models\EducLabCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedDepartment;
use App\Models\EducOfferedDiscount;
use App\Models\EducOfferedDiscountFeesType;
use App\Models\EducOfferedDiscountList;
use App\Models\EducOfferedFees;
use App\Models\EducOfferedLabCourses;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducProgramsCode;
use App\Models\EducTimeMax;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class SchoolYearController extends Controller
{
   /**
     * Display a listing of the offered school years.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the list of offered school years.
     */
    public function index()
    {
        $data = array();

        // Retrieve a list of offered school years along with their associated grade periods, ordered by ID in descending order
        $query = EducOfferedSchoolYear::with('grade_period')->orderBy('id', 'DESC')->get();

        // Count the number of offered school years in the query result
        $count = $query->count();

        if ($count > 0) {
            $x = 1;

            // Iterate through each offered school year in the query result
            foreach ($query as $r) {
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->year_from . ' - ' . $r->year_to;
                $data_list['f3'] = $r->grade_period->name;
                $data_list['f4'] = date('M d, Y', strtotime($r->date_from)) . ' - ' . date('M d, Y', strtotime($r->date_to)) . '<br> Extension:<br>' . date('M d, Y', strtotime($r->date_extension));
                $data_list['f5'] = date('M d, Y', strtotime($r->enrollment_from)) . ' - ' . date('M d, Y', strtotime($r->enrollment_to)) . '<br> Extension:<br>' . date('M d, Y', strtotime($r->enrollment_extension));
                $data_list['f6'] = date('M d, Y', strtotime($r->add_dropping_from)) . ' - ' . date('M d, Y', strtotime($r->add_dropping_to)) . '<br> Extension:<br>' . date('M d, Y', strtotime($r->add_dropping_extension));
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-xs programsViewModal"
                                        data-id="' . $r->id . '">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan btn-xs schoolYearEdit"
                                        data-id="' . $r->id . '">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return a JSON response containing the list of offered school years
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
    public function store(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $grade_period = $request->grade_period;
        $date_duration = $request->date_duration;
        $date_extension = date('Y-m-d',strtotime($request->date_extension));
        $enrollment_duration = $request->enrollment_duration;
        $enrollment_extension = date('Y-m-d',strtotime($request->enrollment_extension));
        $add_dropping_duration = $request->add_dropping_duration;
        $add_dropping_extension = date('Y-m-d',strtotime($request->add_dropping_extension));
        $exp_date_duration = explode(' - ',$date_duration);
        $date_from = date('Y-m-d',strtotime($exp_date_duration[0]));
        $date_to = date('Y-m-d',strtotime($exp_date_duration[0]));
        $exp_enrollment_duration = explode(' - ',$enrollment_duration);
        $enrollment_from = date('Y-m-d',strtotime($exp_enrollment_duration[0]));
        $enrollment_to = date('Y-m-d',strtotime($exp_enrollment_duration[0]));
        $exp_add_dropping_duration = explode(' - ',$add_dropping_duration);
        $add_dropping_from = date('Y-m-d',strtotime($exp_add_dropping_duration[0]));
        $add_dropping_to = date('Y-m-d',strtotime($exp_add_dropping_duration[0]));

        if($date_extension<=$date_to){
            $date_extension = $date_to;
        }

        if($enrollment_extension<=$enrollment_to){
            $enrollment_extension = $enrollment_to;
        }

        if($add_dropping_extension<=$add_dropping_to){
            $add_dropping_extension = $add_dropping_to;
        }

        $check = EducOfferedSchoolYear::where('year_from',$year_from)
                    ->where('year_to',$year_to)
                    ->where('grade_period_id',$grade_period)
                    ->first();
        if($check==NULL){
            $time_max = EducTimeMax::first();
            try{
                $insert = new EducOfferedSchoolYear;
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->grade_period_id = $grade_period;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->time_from = $time_max->time_from;
                $insert->time_to = $time_max->time_to;
                $insert->min_student = $time_max->min_student;
                $insert->max_student = $time_max->max_student;
                $insert->date_extension = $date_extension;
                $insert->enrollment_from = $enrollment_from;
                $insert->enrollment_to = $enrollment_to;
                $insert->enrollment_extension = $enrollment_extension;
                $insert->add_dropping_from = $add_dropping_from;
                $insert->add_dropping_to = $add_dropping_to;
                $insert->add_dropping_extension = $add_dropping_extension;
                $insert->unit_limit = $time_max->unit_limit;
                $insert->updated_by = $updated_by;
                $insert->save();
                $id = $insert->id;
                $this->offerProgramsSubmit($id);
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
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result,
                          'id' => $id);
        return response()->json($response);
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
    public function edit(Request $request){
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();

        if($query==NULL){
            return view('layouts/error/404');
        }

        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/schoolYear/modalEdit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2){
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            $date_duration = explode(' - ',$request->date_duration);
            $date_extension = date('Y-m-d',strtotime($request->date_extension));
            $enrollment_duration = explode(' - ',$request->enrollment_duration);
            $enrollment_extension = date('Y-m-d',strtotime($request->enrollment_extension));
            $add_dropping_duration = explode(' - ',$request->add_dropping_duration);
            $add_dropping_extension = date('Y-m-d',strtotime($request->add_dropping_extension));
            $date_from = date('Y-m-d',strtotime($date_duration[0]));
            $date_to = date('Y-m-d',strtotime($date_duration[1]));
            $enrollment_from = date('Y-m-d',strtotime($enrollment_duration[0]));
            $enrollment_to = date('Y-m-d',strtotime($enrollment_duration[1]));
            $add_dropping_from = date('Y-m-d',strtotime($add_dropping_duration[0]));
            $add_dropping_to = date('Y-m-d',strtotime($add_dropping_duration[1]));
            $time_from = date('H:i:s',strtotime($request->time_from));
            $time_to = date('H:i:s',strtotime($request->time_to));
            if($date_extension<=$date_to){
                $date_extension = $date_to;
            }
            if($enrollment_extension<=$enrollment_to){
                $enrollment_extension = $enrollment_to;
            }
            if($add_dropping_extension<=$add_dropping_to){
                $add_dropping_extension = $add_dropping_to;
            }
            try{
                EducOfferedSchoolYear::where('id', $id)
                            ->update(['time_from' => $time_from,
                                      'time_to' => $time_to,
                                      'date_from' => $date_from,
                                      'date_to' => $date_to,
                                      'date_extension' => $date_extension,
                                      'enrollment_from' => $enrollment_from,
                                      'enrollment_to' => $enrollment_to,
                                      'enrollment_extension' => $enrollment_extension,
                                      'add_dropping_from' => $add_dropping_from,
                                      'add_dropping_to' => $add_dropping_to,
                                      'add_dropping_extension' => $add_dropping_extension,
                                      'updated_by' => $updated_by,
                                      'updated_at' => date('Y-m-d H:i:s'),
                                    ]);
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
        $response = array('result' => $result);
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function offerProgramsSubmit($id){
        $user = Auth::user();
        $updated_by = $user->id;
        $time_max = EducTimeMax::first();
        $result = 'error';
        try{
            $school_year = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
            $grade_period = $school_year->grade_period_id;
            $grade_period_period = $school_year->grade_period->period;
            $program_level_ids = EducProgramLevel::where('period',$grade_period_period)->pluck('id')->toArray();

            $query = EducProgramsCode::with('program')->where('status_id', 1)
                                                ->whereHas('program', function($query) use ($program_level_ids){
                                                    $query->whereIn('program_level_id', $program_level_ids);
                                                })
                                                //->whereIn('program_id',$program_idss)
                                                ->get()
                                                ->map(function($query) use ($id,$updated_by) {
                                                return [
                                                    'school_year_id' => $id,
                                                    'program_id' => $query->program_id,
                                                    'program_code_id' => $query->id,
                                                    'department_id' => $query->program->department_id,
                                                    'branch_id' => $query->branch_id,
                                                    'name' => $query->name,
                                                    'status_id' => 1,
                                                    'updated_by' => $updated_by,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                ];
                                            })->toArray();
                EducOfferedPrograms::insert($query);

                $programs_id = EducOfferedPrograms::where('school_year_id',$id)->pluck('id')->toArray();
                $department_ids = EducOfferedPrograms::where('school_year_id',$id)->pluck('department_id')->toArray();

                $query = EducDepartments::whereIn('id',$department_ids)->get()
                                            ->map(function($query) use ($id,$updated_by) {
                                            return [
                                                    'school_year_id' => $id,
                                                    'department_id' => $query->id,
                                                    'name' => $query->name,
                                                    'shorten' => $query->shorten,
                                                    'code' => $query->code,
                                                    'updated_by' => $updated_by,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                            ];
                                        })->toArray();
                EducOfferedDepartment::insert($query);

                $query = EducOfferedPrograms::join('educ_curriculum',
                                                    'educ__offered_programs.program_id', '=', 'educ_curriculum.program_id')
                                            ->join('educ_curriculum_branch', function ($join) {
                                                $join->on('educ__offered_programs.branch_id', '=', 'educ_curriculum_branch.branch_id')
                                                    ->on('educ_curriculum.id', '=', 'educ_curriculum_branch.curriculum_id');
                                            })
                                            ->select('educ_curriculum.id',
                                                    'educ_curriculum.code',
                                                    DB::raw('educ__offered_programs.id as offered_program_id'))
                                            ->where('educ__offered_programs.school_year_id', $id)
                                            ->where('educ_curriculum_branch.status_id',1)
                                            ->get()
                                            ->map(function($query) use ($updated_by) {
                                            return [
                                                    'offered_program_id' => $query->offered_program_id,
                                                    'curriculum_id' => $query->id,
                                                    'code' => $query->code,
                                                    'status_id' => 1,
                                                    'updated_by' => $updated_by,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                            ];
                                        })->toArray();
                EducOfferedCurriculum::insert($query);

                $query = EducOfferedCurriculum::with('offered_program.program','curriculum')
                            ->whereIn('offered_program_id', $programs_id)
                            ->get();
                foreach($query as $row){
                    $branch_id = $row->offered_program->branch_id;
                    $courses = EducCourses::with('grade_level')->where('curriculum_id', $row->curriculum_id)
                                    ->where('status_id', 1)
                                    ->where('grade_period_id',$grade_period)
                                    ->where('shorten','NOT LIKE','%nstp%')
                                    ->get();
                    foreach($courses as $course){
                        $datas[] = [
                                    'school_year_id' => $id,
                                    'branch_id' => $branch_id,
                                    'offered_curriculum_id' => $row->id,
                                    'course_id' => $course->id,
                                    'min_student' => $time_max->min_student,
                                    'max_student' => $time_max->max_student,
                                    'code' => $course->code,
                                    'status_id' => 1,
                                    'year_level' => $course->grade_level->level,
                                    'section' => 1,
                                    'hours' => $course->units,
                                    'minutes' => 0,
                                    'section_code' => date('y',strtotime($row->curriculum->year_from.'-01-01')).
                                                        $row->offered_program->program->code.'1'.
                                                        $course->grade_level->level,
                                    'updated_by' => $updated_by,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                            ];
                    }
                }
                EducOfferedCourses::insert($datas);

                $query = EducDiscount::where('status_id', 1)
                            ->get();
                if($query->count()>0){
                    foreach($query as $row){
                        $discount_id = $row->id;
                        $insert = new EducOfferedDiscount();
                        $insert->school_year_id = $id;
                        $insert->discount_id = $discount_id;
                        $insert->name = $row->name;
                        $insert->percent = $row->percent;
                        $insert->option_id = $row->option_id;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $offered_discount_id = $insert->id;

                        $fees_type = EducDiscountFeesType::where('discount_id', $discount_id)
                            ->get();
                        if($fees_type->count()>0){
                            foreach($fees_type as $row_fees)
                                {$insert = new EducOfferedDiscountFeesType();
                                $insert->school_year_id = $id;
                                $insert->offered_discount_id = $offered_discount_id;
                                $insert->fees_type_id = $row_fees->fees_type_id;
                                $insert->updated_by = $updated_by;
                                $insert->save();
                            }
                        }
                        $list = EducDiscountList::where('discount_id', $discount_id)
                            ->get();
                        if($list->count()>0){
                            foreach($list as $row_list){
                                $insert = new EducOfferedDiscountList();
                                $insert->school_year_id = $id;
                                $insert->offered_discount_id = $offered_discount_id;
                                $insert->program_id = $row_list->program_id;
                                $insert->user_id = $row_list->user_id;
                                $insert->updated_by = $updated_by;
                                $insert->save();
                            }
                        }
                    }
                }

                $query = EducFeesPeriod::where('grade_period_id', $grade_period)
                            ->get();
                if($query->count()>0){
                    foreach($query as $row){
                        $insert = new EducOfferedFees();
                        $insert->school_year_id = $id;
                        $insert->fees_id = $row->fees_id;
                        $insert->fees_type_id = $row->fees->type_id;
                        $insert->branch_id = $row->branch_id;
                        $insert->program_level_id = $row->program_level_id;
                        $insert->grade_period_id = $row->grade_period_id;
                        $insert->amount = $row->amount;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }

                $query = EducLabCourses::get();
                if($query->count()>0){
                    foreach($query as $row){
                        $insert = new EducOfferedLabCourses();
                        $insert->school_year_id = $id;
                        $insert->lab_group_id = $row->lab_group_id;
                        $insert->program_level_id = $row->program_level_id;
                        $insert->course_code = $row->course_code;
                        $insert->amount = $row->amount;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
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
    private function updateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'department' => 'required|numeric',
            'name' => 'required|string',
            'shorten' => 'required|string',
            'code' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'department.required' => 'Department is required.',
            'department.numeric' => 'Department must be a number.',
            'name.required' => 'Name is required.',
            'shorten.required' => 'Shorten is required.',
            'code.required' => 'Code is required.',
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
