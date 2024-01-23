<?php

namespace App\Http\Controllers\RIMS\SchoolYear;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducDepartments;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedDepartment;
use App\Models\EducOfferedPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducTimeMax;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class CourseOpenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get user access level and other data from the request
        $user_access_level = $request->session()->get('user_access_level');

        // Validate the request
        $validator = $this->indexValidateRequest($request);
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $branch = EducBranch::get();
        $data = array(
            'id' => $id,
            'branch' => $branch,
            'user_access_level' => $user_access_level
        );

        // Return the view with the data
        return view('rims/schoolYear/coursesOpenModal', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not implemented
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

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;
            $school_year_id = $request->id;
            $course_ids = $request->course_ids;
            $branch = $request->branch;
            $time_max = EducTimeMax::first();

            // Retrieve courses based on specified conditions
            $courses = EducCourses::with('curriculum.programs', 'grade_level')
                ->whereIn('id', $course_ids)
                ->whereDoesntHave('courses', function ($query) use ($school_year_id) {
                    $query->whereHas('curriculum', function ($subQuery) use ($school_year_id) {
                        $subQuery->whereHas('offered_program', function ($subQuery) use ($school_year_id) {
                            $subQuery->where('school_year_id', $school_year_id);
                        });
                    });
                })
                ->get();

            $count = $courses->count();

            if ($count > 0) {
                // Extract department, program, and curriculum data
                $departments = $courses->pluck('curriculum.programs.department_id')->toArray();
                $programs = $courses->pluck('curriculum.program_id')->toArray();
                $curriculums = $courses->pluck('curriculum_id')->toArray();

                // Store departments, programs, and curriculums
                $this->storeDepartments($departments, $school_year_id, $updated_by);
                $this->storePrograms($programs, $branch, $school_year_id, $updated_by);
                $this->storeCurriculums($curriculums, $branch, $school_year_id, $updated_by);

                // Iterate through courses and store offered courses
                foreach ($courses as $r) {
                    $offered_curriculum = EducOfferedCurriculum::with('offered_program.program')
                        ->whereHas('offered_program', function ($subQuery) use ($school_year_id, $branch) {
                            $subQuery->where('school_year_id', $school_year_id);
                            $subQuery->whereHas('program_code', function ($subQuery) use ($branch) {
                                $subQuery->where('branch_id', $branch);
                            });
                        })
                        ->where('curriculum_id', $r->curriculum_id)
                        ->first();

                    if ($offered_curriculum) {
                        $insert = new EducOfferedCourses();
                        $insert->offered_curriculum_id = $offered_curriculum->id;
                        $insert->course_id = $r->id;
                        $insert->min_student = $time_max->min_student;
                        $insert->max_student = $time_max->max_student;
                        $insert->code = $r->code;
                        $insert->hours = $r->units;
                        $insert->year_level = $r->grade_level->level;
                        $insert->section = 1;
                        $insert->section_code = $offered_curriculum->offered_program->name . $offered_curriculum->offered_program->program->code . '1' . $r->grade_level->level . $offered_curriculum->code;
                        $insert->status_id = 1;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }

                // Commit the database transaction
                DB::commit();

                return response()->json(['result' => 'success']);
            } else {
                // Rollback the database transaction and return an error response
                DB::rollback();
                return response()->json(['result' => 'error']);
            }
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $data = array();

        // Validate the request using a custom validation method
        $validator = $this->showValidateRequest($request);

        // Check if validation fails and return validation errors if it does
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400);
        }

        // Get input parameters from the request
        $school_year_id = $request->id;
        $course_code = $request->course_code;
        $branch = $request->branch;

        // Query courses based on specified conditions
        $query = EducCourses::with('curriculum.programs', 'grade_level', 'grade_period')
            ->where('code', $course_code)
            ->whereDoesntHave('courses', function ($query) use ($school_year_id, $branch) {
                $query->whereHas('curriculum', function ($subQuery) use ($school_year_id, $branch) {
                    $subQuery->whereHas('offered_program', function ($subQuery) use ($school_year_id, $branch) {
                        $subQuery->where('school_year_id', $school_year_id);
                        $subQuery->whereHas('program_code', function ($subQuery) use ($branch) {
                            $subQuery->where('branch_id', $branch);
                        });
                    });
                });
            })
            ->get();

        // Get the count of queried courses
        $count = $query->count();

        if ($count > 0) {
            $x = 1;
            foreach ($query as $r) {
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->code;
                $data_list['f3'] = $r->name;
                $data_list['f4'] = $r->curriculum->programs->shorten;
                $data_list['f5'] = $r->curriculum->year_from . '-' . $r->curriculum->year_to . '(' . $r->curriculum->code . ')';
                $data_list['f6'] = $r->grade_period->name;
                $data_list['f7'] = $r->grade_level->name;
                $data_list['f8'] = '<input type="checkbox" class="form-control courseCheck"
                                    data-id="' . $r->id . '">';
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return the data as JSON response
        return response()->json($data);
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
     * Store departments that are not already offered in a given school year.
     *
     * @param array $ids Array of department IDs to be stored.
     * @param int $school_year_id The school year ID.
     * @param int $updated_by The ID of the user who updated the data.
     */
    private function storeDepartments($ids, $school_year_id, $updated_by)
    {
        try {
            $query = EducDepartments::whereIn('id', $ids)
                ->whereDoesntHave('offered_department', function ($query) use ($school_year_id) {
                    $query->where('school_year_id', $school_year_id);
                })
                ->get();

            if ($query->count() > 0) {
                foreach ($query as $r) {
                    $insert = new EducOfferedDepartment();
                    $insert->school_year_id = $school_year_id;
                    $insert->department_id = $r->id;
                    $insert->name = $r->name;
                    $insert->shorten = $r->shorten;
                    $insert->code = $r->code;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Store programs that are not already offered in a given school year and branch.
     *
     * @param array $ids Array of program IDs to be stored.
     * @param int $branch The branch ID.
     * @param int $school_year_id The school year ID.
     * @param int $updated_by The ID of the user who updated the data.
     */
    private function storePrograms($ids, $branch, $school_year_id, $updated_by)
    {
        try {
            $query = EducProgramsCode::with('program')
                ->whereIn('program_id', $ids)
                ->where('branch_id', $branch)
                ->whereHas('program', function ($subQuery) use ($school_year_id) {
                    $subQuery->whereDoesntHave('offered_program', function ($subQuery) use ($school_year_id) {
                        $subQuery->where('school_year_id', $school_year_id);
                    });
                })
                ->get();

            if ($query->count() > 0) {
                foreach ($query as $r) {
                    $insert = new EducOfferedPrograms();
                    $insert->school_year_id = $school_year_id;
                    $insert->program_id = $r->program_id;
                    $insert->program_code_id = $r->id;
                    $insert->department_id = $r->program->department_id;
                    $insert->name = $r->name;
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Store curriculums that are not already offered in a given school year, branch, and program code.
     *
     * @param array $ids Array of curriculum IDs to be stored.
     * @param int $branch The branch ID.
     * @param int $school_year_id The school year ID.
     * @param int $updated_by The ID of the user who updated the data.
     */
    private function storeCurriculums($ids, $branch, $school_year_id, $updated_by)
    {
        try {
            $query = EducCurriculum::whereIn('id', $ids)
                ->whereDoesntHave('offered_curriculums', function ($subQuery) use ($school_year_id, $branch) {
                    $subQuery->whereHas('offered_program', function ($subQuery) use ($school_year_id, $branch) {
                        $subQuery->where('school_year_id', $school_year_id);
                        $subQuery->whereHas('program_code', function ($subQuery) use ($branch) {
                            $subQuery->where('branch_id', $branch);
                        });
                    });
                })
                ->get();

            if ($query->count() > 0) {
                foreach ($query as $r) {
                    $offered_program = EducOfferedPrograms::where('school_year_id', $school_year_id)
                        ->whereHas('program_code', function ($subQuery) use ($branch) {
                            $subQuery->where('branch_id', $branch);
                        })
                        ->where('program_id', $r->program_id)
                        ->first();

                    if ($offered_program) {
                        $insert = new EducOfferedCurriculum();
                        $insert->offered_program_id = $offered_program->id;
                        $insert->curriculum_id = $r->id;
                        $insert->code = $r->code;
                        $insert->status_id = 1;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
            }
        } catch (Exception $e) {
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
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'course_code' => 'nullable|string',
            'branch' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'School ID is required.',
            'id.numeric' => 'School ID must be a number.',
            'course_code.string' => 'Course Code must be a string.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
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
            'id' => 'required|numeric',
            'course_ids' => 'required|array',
            'branch' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'course_ids.required' => 'Course is required.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
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
