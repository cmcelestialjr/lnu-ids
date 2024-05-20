<?php

namespace App\Http\Controllers\RIMS;

use App\Http\Controllers\Controller;
use App\Models\EducCoursesNstp;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class NstpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize an empty data array
        $data = array();

        // Validate the request
        $validator = $this->indexValidateRequest($request);
        if ($validator->fails()) {
            return response()->json($data); // Return validation errors
        }

        // Get the requested from the request
        $school_year_id = $request->school_year_id;
        $branch_id = $request->branch_id;

        $check = EducOfferedSchoolYear::where('id',$school_year_id)
            ->whereHas('offered_program', function ($q) use ($branch_id){
                $q->where('branch_id', $branch_id);
            })->first();

        if (!$check) {
            return response()->json($data);
        }

        $query = EducOfferedCourses::with('nstp')
            ->whereHas('school_year.offered_program', function ($q) use ($school_year_id,$branch_id){
                $q->where('school_year_id', $school_year_id);
                $q->where('branch_id', $branch_id);
            })
            ->get();

        // Get the count of results
        $count = $query->count();

        if ($count > 0) {
            $x = 1;
            foreach ($query as $r) {
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->nstp->shorten;
                $data_list['f3'] = $r->section_code;
                $data_list['f4'] = $r->max_student;
                $data_list['f5'] = '<button class="btn btn-info">'.$r->max_student.'</button>';
                array_push($data, $data_list);
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
        $nstps = EducCoursesNstp::get();

        $data = array('nstps' => $nstps);

        // Return a view with the data
        return view('rims/sections/nstpNewModal', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get user access level
        $user_access_level = $request->session()->get('user_access_level');

        // Check user access level
        if (!in_array($user_access_level, [1, 2, 3])) {
            return response()->json(['result' => 'error']);
        }

        // Validate the request
        $validator = $this->storeValidateRequest($request);
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        // Get the requested from the request
        $school_year_id = $request->school_year_id;
        $branch_id = $request->branch_id;
        $nstp_id = $request->nstp_id;
        $max_student = $request->max_student;

        $check = EducCoursesNstp::find($nstp_id);
        $check_school_year = EducOfferedSchoolYear::where('id',$school_year_id)
            ->whereHas('offered_program', function ($q) use ($branch_id){
                $q->where('branch_id', $branch_id);
            })->first();

        if(!$check && !$check_school_year){
            return response()->json(['result'=> 'error'], 400);
        }

       try {
            $user = Auth::user();
            $updated_by = $user->id;

            $code = 'NSTP-101';
            if($check_school_year->grade_period_id==1){
                $code = 'NSTP-102';
            }

            $getSection = EducOfferedCourses::where('school_year_id',$school_year_id)
                ->where('branch_id',$branch_id)
                ->where('nstp_id',$nstp_id)
                ->orderBy('section','DESC')
                ->first();
            $section = 1;
            if($getSection){
                $section = $getSection->section+1;
            }

            $insert = new EducOfferedCourses();
            $insert->school_year_id = $school_year_id;
            $insert->branch_id = $branch_id;
            $insert->nstp_id = $nstp_id;
            $insert->min_student = 10;
            $insert->max_student = $max_student;
            $insert->code = $code;
            $insert->section = $section;
            $insert->section_code = $check->shorten.$section;
            $insert->updated_by = $updated_by;
            $insert->save();

            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            return $this->handleOtherError($e);
        }
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

    public function getCount(Request $request)
    {
        // Validate the request
        $validator = $this->indexValidateRequest($request);
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        // Get the requested from the request
        $school_year_id = $request->school_year_id;
        $branch_id = $request->branch_id;

        $check = EducOfferedSchoolYear::where('id',$school_year_id)
            ->whereHas('offered_program', function ($q) use ($branch_id){
                $q->where('branch_id', $branch_id);
            })->first();

        if (!$check) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        $cwts_section_count = EducOfferedCourses::where('school_year_id',$school_year_id)
                ->where('branch_id',$branch_id)
                ->where('nstp_id',1)
                ->get()->count();
        $lts_section_count = EducOfferedCourses::where('school_year_id',$school_year_id)
                ->where('branch_id',$branch_id)
                ->where('nstp_id',2)
                ->get()->count();
        $rotc_section_count = EducOfferedCourses::where('school_year_id',$school_year_id)
                ->where('branch_id',$branch_id)
                ->where('nstp_id',3)
                ->get()->count();

        $cwts_student_count = StudentsCourses::whereHas('course', function ($q) use ($school_year_id,$branch_id){
                $q->where('school_year_id',$school_year_id);
                $q->where('branch_id',$branch_id);
                $q->where('nstp_id',1);
            })->get()->count();

        $lts_student_count = StudentsCourses::whereHas('course', function ($q) use ($school_year_id,$branch_id){
                $q->where('school_year_id',$school_year_id);
                $q->where('branch_id',$branch_id);
                $q->where('nstp_id',2);
            })->get()->count();

        $rotc_student_count = StudentsCourses::whereHas('course', function ($q) use ($school_year_id,$branch_id){
                $q->where('school_year_id',$school_year_id);
                $q->where('branch_id',$branch_id);
                $q->where('nstp_id',3);
            })->get()->count();
        return response()->json(['result' => 'success',
            'cwts_section_count' => $cwts_section_count,
            'cwts_student_count' => $cwts_student_count,
            'lts_section_count' => $lts_section_count,
            'lts_student_count' => $lts_student_count,
            'rotc_section_count' => $rotc_section_count,
            'rotc_student_count' => $rotc_student_count,
        ]);
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
            'school_year_id' => 'required|numeric',
            'branch_id' => 'required|numeric'
        ];

        $customMessages = [

        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'school_year_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'nstp_id' => 'required|numeric',
            'max_student' => 'required|numeric'
        ];

        $customMessages = [

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
