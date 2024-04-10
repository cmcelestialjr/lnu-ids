<?php

namespace App\Http\Controllers\RIMS\Student;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\StudentsStatus;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class SelectProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }

        $program_level = EducProgramLevel::get();
        $branch = EducBranch::get();
        $student_status = StudentsStatus::get();
        $data = array(
            'student' => $student,
            'branch' => $branch,
            'program_level' => $program_level,
            'student_status' => $student_status
        );
        return view('rims/student/selectProgramModal',$data);
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
    public function showProgram(Request $request)
    {
        $program_level_id = $request->program_level;
        $program_level = EducProgramLevel::where('id',$program_level_id)
            ->first(['id']);
        if($program_level==NULL){
            return response()->json(['result' => 'error']);
        }

        $list = EducPrograms::where('program_level_id',$program_level_id)
            ->orderBy('name','ASC')
            ->get();
        return response()->json(['result' => 'success',
                                 'list' => $list
                                ]);
    }

    /**
     * Display the specified resource.
     */
    public function showCurriculum(Request $request)
    {
        $program_id = $request->program;
        $program = EducPrograms::where('id',$program_id)
            ->first(['id']);
        if($program==NULL){
            return response()->json(['result' => 'error']);
        }

        $list = EducCurriculum::where('program_id',$program_id)
            ->orderBy('year_from','DESC')
            ->get();
        return response()->json(['result' => 'success',
                                 'list' => $list
                                ]);
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
        // Validate the request
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Check if the user has the required access level (1, 2, 3)
        if($user_access_level != 1 && $user_access_level != 2 && $user_access_level != 3){
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $branch = $request->branch;
        $program_level = $request->program_level;
        $program = $request->program;
        $curriculum = $request->curriculum;
        $year_from = $request->year_from;
        $student_status = $request->student_status;
        $adopt_same_course = $request->adopt_same_course;

        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }

        $checkBranch = EducBranch::find($branch);
        // Check if the branch is valid
        if($checkBranch==NULL){
            return response()->json(['result' => 'error']);
        }

        $checkCurriculum = EducCurriculum::where('id',$curriculum)
            ->whereHas('programs', function ($subQuery) use ($program_level,$program) {
                $subQuery->where('id', $program);
                $subQuery->where('program_level_id', $program_level);
            })->first();
            
        // Check if the curriculum is valid
        if($checkCurriculum==NULL){
            return response()->json(['result' => 'error']);
        }

        $checkStudentStatus = StudentsStatus::find($student_status);
        // Check if the student status is valid
        if($checkStudentStatus==NULL){
            return response()->json(['result' => 'error']);
        }

        $program_code = EducProgramsCode::with('program')
            ->where('program_id',$program)
            ->where('branch_id',$branch)
            ->first();
        // Check if the program code is valid
        if($program_code==NULL){
            return response()->json(['result' => 'error']);
        }

        DB::beginTransaction();
        try{
            // Commit the database transaction
            DB::commit();

            $user = Auth::user();
            $updated_by = $user->id;

            $program_code_id = $program_code->id;
            $program_name = $program_code->program->name;
            $program_shorten = $program_code->program->shorten;

            // Update Student Information
            $insert = new StudentsProgram();
            $insert->user_id = $id;
            $insert->program_id = $program;
            $insert->program_level_id = $program_level;
            $insert->program_code_id = $program_code_id;
            $insert->curriculum_id = $curriculum;
            $insert->program_name = $program_name;
            $insert->program_shorten = $program_shorten;
            $insert->year_from = $year_from;
            $insert->from_school = 'Leyte Normal University';
            $insert->student_status_id = $program;
            $insert->updated_by = $updated_by;
            $insert->save();
            $student_program_id = $insert->id;

            // Update Student Information
            $insert = new StudentsInfo();
            $insert->user_id = $id;
            $insert->id_no = $student->stud_id;
            $insert->program_id = $program;
            $insert->program_level_id = $program_level;
            $insert->program_code_id = $program_code_id;
            $insert->curriculum_id = $curriculum;
            $insert->program_name = $program_name;
            $insert->program_shorten = $program_shorten;
            $insert->student_status_id = $program;
            $insert->updated_by = $updated_by;
            $insert->save();

            if($adopt_same_course==1){
                $student_courses = StudentsCourses::where('user_id')
                    ->where('student_program_id',NULL)
                    ->get();
                if($student_courses->count()>0){
                    foreach($student_courses as $course){
                        $select_course = EducCourses::where('curriculum_id',$curriculum)
                            ->where('code',$course->course_code)
                            ->first();
                        if($select_course){
                            // Update student course
                            StudentsCourses::where('id', $course->id)
                                ->update(['student_program_id' => $student_program_id,
                                        'course_id' => $select_course->id,
                                        'grade_level_id' => $select_course->grade_level_id,
                                        'program_level_id' => $program_level,
                                        'course_desc' => $select_course->description,
                                        'course_units' => $select_course->units,
                                        'updated_by' => $updated_by,
                                        'updated_at' => now(),
                                        ]);
                        }
                    }
                }
            }

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
            'branch' => 'required|numeric',
            'program_level' => 'required|numeric',
            'program' => 'required|numeric',
            'curriculum' => 'required|numeric',
            'year_from' => 'required|date_format:Y',
            'student_status' => 'required|numeric',
            'adopt_same_course' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.',
            'program.required' => 'Program is required.',
            'program.numeric' => 'Program must be a number.',
            'curriculum.required' => 'Curriculum is required.',
            'curriculum.numeric' => 'Curriculum must be a number.',
            'year_from.required' => 'Year From Status is required.',
            'year_from.numeric' => 'Year From Status must be a valid Year.',
            'student_status.required' => 'Student Status is required.',
            'student_status.numeric' => 'Student Status must be a number.',
            'adopt_same_course.required' => 'Adopt Same Course is required.',
            'adopt_same_course.numeric' => 'Adopt Same Course must be a number.'
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
