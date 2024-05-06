<?php

namespace App\Http\Controllers\RIMS\Curriculums;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducCurriculumBranch;
use App\Models\EducDepartments;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducYearLevel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class CurriculumsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the user access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Initialize data array
        $data = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($data);
        }

        // Get the request from the request
        $status_id = $request->status_id;
        $level = $request->level;
        $branch = $request->branch;

        $status_check = EducCourseStatus::where('id', $status_id)->first();
        $level_check = EducProgramLevel::where('id', $level)->first();
        $branch_check = EducBranch::where('id', $branch)->first();

        if($status_id>0){
            // Check if status if exists
            if ($status_check==NULL) {
                return response()->json($data);
            }
        }

        // Check if status if exists
        if ($level_check==NULL && $branch_check==NULL) {
            return response()->json($data);
        }

        // Query the curriculums with related data
        $query = EducCurriculumBranch::with('curriculum.programs','status')
            ->where('branch_id', $branch)
            ->whereHas('curriculum.programs', function ($q) use ($level,$branch) {
                $q->where('program_level_id', $level);
            });
        if ($status_id>0) {
            $query = $query->where('status_id', $status_id);
        }
        $query = $query->get()
                    ->map(function($query) {
                        return [
                            'id' => $query->id,
                            'year_from' => $query->curriculum->year_from,
                            'program' => $query->curriculum->programs->shorten.'-'.$query->curriculum->programs->name,
                            'status_id' => $query->status_id,
                            'status' => $query->status->name
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = '<span id="yearFromSpan'.$x.'">'.$r['year_from'].'</span>';
                $data_list['f3'] = $r['program'];

                if ($user_access_level == 1 || $user_access_level == 2) {
                    $data_list['f5'] = '<button class="btn btn-info btn-info-scan btn-sm editModal"
                                            data-id="'.$r['id'].'"
                                            data-x="'.$x.'">
                                            <span class="fa fa-edit"></span> Edit
                                        </button>';
                }

                if ($r['status_id'] == 1) {
                    $status = '<button class="btn btn-success" id="statusBtn'.$x.'">'.$r['status'].'</button>';
                } else {
                    $status = '<button class="btn btn-danger" id="statusBtn'.$x.'">'.$r['status'].'</button>';
                }

                $data_list['f4'] = $status;

                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan btn-sm viewModal"
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
        $statuses = EducCourseStatus::get();
        $levels = EducProgramLevel::where('program','w')->get();
        $programs = EducPrograms::where('program_level_id','6')->get();
        $data = array(
            'levels' => $levels,
            'statuses' => $statuses,
            'programs' => $programs
        );
        return view('rims/curriculums/newModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $program = $request->program;
        $name = $request->name;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $status = $request->status;
        $remarks = $request->remarks;
        $branch = $request->branch;

        $curriculum_check = EducPrograms::find($program);
        $status_check = EducCourseStatus::find($status);
        $branch_check = EducBranch::find($branch);

        if ($curriculum_check==NULL && $status_check==NULL && $branch_check==NULL) {
            return response()->json(['result' => 'error']);
        }

        $check_exists = EducCurriculum::where('program_id',$program)
            ->where('year_from',$year_from)
            ->where('year_from','<>',$curriculum_check->year_from)
            ->first();

        if ($check_exists) {
            return response()->json(['result' => 'exists']);
        }

        try {
            $insert = new EducCurriculum();
            $insert->program_id = $program;
            $insert->name = $name;
            $insert->year_from = $year_from;
            $insert->year_to = $year_to;
            $insert->status_id = 1;
            $insert->remarks = $remarks;
            $insert->updated_by = $updated_by;
            $insert->save();
            $curriculum_id = $insert->id;

            $insert = new EducCurriculumBranch();
            $insert->curriculum_id = $curriculum_id;
            $insert->branch_id = $branch;
            $insert->status_id = $status;
            $insert->updated_by = $updated_by;
            $insert->save();

            $branches = EducBranch::where('id','!=',$branch)->get();
            if($branches->count() > 0){
                foreach($branches as $row){
                    $insert = new EducCurriculumBranch();
                    $insert->curriculum_id = $curriculum_id;
                    $insert->branch_id = $row->id;
                    $insert->status_id = 2;
                    $insert->updated_by = $updated_by;
                    $insert->save();
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
     * Display the specified resource.
     */
    public function viewModal(Request $request, int $id)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $curriculum = EducCurriculum::with('courses','programs','status')
            ->where('id',$id)
            ->first();

        if($curriculum==NULL){
            return view('layouts/error/404');
        }

        // Retrieve course statuses
        $status = EducCourseStatus::get();

        // Retrieve year levels associated with the program's program level
        $year_level = EducYearLevel::where('program_level_id', $curriculum->programs->program_level_id)
            ->orderBy('level', 'ASC')
            ->get();

        $data = array(
            'id' => $id,
            'curriculum' => $curriculum,
            'status' => $status,
            'year_level' => $year_level,
            'user_access_level' => $user_access_level
        );
        return view('rims/curriculums/viewModal',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editModal(Request $request, int $id)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $access_level_array = array(1,2);
        $x = $request->x;

        $curriculum = EducCurriculumBranch::with([
                'curriculum.courses',
                'curriculum.programs',
                'status'
            ])
            ->where('id',$id)
            ->first();

        if($curriculum==NULL && !in_array($user_access_level, $access_level_array)){
            return view('layouts/error/404');
        }

        // Retrieve course statuses
        $statuses = EducCourseStatus::get();

        $data = array(
            'id' => $id,
            'x' => $x,
            'curriculum' => $curriculum,
            'statuses' => $statuses
        );
        return view('rims/curriculums/editModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $name = $request->name;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $status = $request->status;
        $remarks = $request->remarks;
        $branch = $request->branch;

        $curriculum_check = EducCurriculum::find($id);
        $status_check = EducCourseStatus::find($status);
        $branch_check = EducBranch::find($branch);

        if ($curriculum_check==NULL && $status_check==NULL && $branch_check==NULL) {
            return response()->json(['result' => 'error']);
        }

        $check_exists = EducCurriculum::where('program_id',$curriculum_check->program_id)
            ->where('year_from',$year_from)
            ->where('year_from','<>',$curriculum_check->year_from)
            ->first();

        if ($check_exists) {
            return response()->json(['result' => 'exists']);
        }

        try {
            $update = EducCurriculum::find($id);
            $update->name = $name;
            $update->year_from = $year_from;
            $update->year_to = $year_to;
            $update->remarks = $remarks;
            $update->updated_by = $updated_by;
            $update->save();

            EducCurriculumBranch::where('curriculum_id', $id)
                ->where('branch_id', $branch)
                ->update([
                    'status_id' => $status,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);


            $program = EducProgramsCode::where('program_id',$update->program_id)
                ->where('branch_id',$branch)
                ->first();
            if($status==1 && $program->status_id>=2) {
                EducProgramsCode::where('id', $program->id)
                    ->update([
                        'status_id' => $status,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            // EducCourses::where('curriculum_id', $id)
            //         ->update([
            //             'status_id' => $status,
            //             'updated_by' => $updated_by,
            //             'updated_at' => date('Y-m-d H:i:s'),
            //         ]);

            return response()->json(['result' => 'success',
                                    'year_from'=> $year_from,
                                    'status'=> $status,
                                    'status_name' => $status_check->name
                                    ]);

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

    public function programList(int $id){
        $level_check = EducProgramLevel::find($id);

        if ($level_check==NULL) {
            return response()->json(['result' => 'error']);
        }

        $programs = EducPrograms::where('program_level_id',$id)->get();

        return response()->json(['result' => 'success',
                                 'programs' => $programs
                                ]);
    }

    public function departments(Request $request){
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        // Get the request from the request
        $status_id = $request->status_id;
        $branch = $request->branch;
        $level = $request->level;

        $status_check = EducCourseStatus::where('id', $status_id)->first();
        $level_check = EducProgramLevel::where('id', $level)->first();
        $branch_check = EducBranch::where('id', $branch)->first();

        // Check if status if exists
        if ($level_check==NULL && $status_check==NULL && $branch_check==NULL) {
            return view('layouts/error/404');
        }

        $departments = EducDepartments::with(['programs' => function ($query) use ($status_id,$branch,$level) {
                $query->where('program_level_id', $level);
                $query->whereHas('codes', function($query) use ($status_id,$branch) {
                    $query->where('status_id', $status_id);
                    $query->where('branch_id', $branch);
                });
                $query->with(['curriculum' => function ($query) use ($status_id,$branch) {
                    $query->with(['branch' => function ($query) use ($status_id,$branch) {
                        $query->where('status_id', $status_id);
                        $query->where('branch_id', $branch);
                    }]);
                }]);
            }])
            ->whereHas('programs', function($query) use ($status_id,$branch,$level) {
                $query->where('program_level_id', $level);
                $query->whereHas('codes', function($query) use ($status_id,$branch) {
                    $query->where('status_id', $status_id);
                    $query->where('branch_id', $branch);
                });
                $query->whereHas('curriculum.branch', function($query) use ($status_id,$branch) {
                    $query->where('status_id', $status_id);
                    $query->where('branch_id', $branch);
                });
            })->whereHas('levels', function($query) use ($level) {
                $query->where('program_level_id', $level);
            })->get();

        $departments1 = EducDepartments::whereHas('levels', function($query) use ($level) {
                $query->where('program_level_id', $level);
            })->get();
        $data = array(
            'departments' => $departments,
            'departments1' => $departments1
        );
        return view('rims/curriculums/departments',$data);
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
            'status_id' => 'required|numeric',
            'branch' => 'required|numeric',
            'level' => 'required|numeric',
        ];

        $customMessages = [
            'status_id.required' => 'Status is required.',
            'status_id.numeric' => 'Status must be a number.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
            'level.required' => 'Level is required.',
            'level.numeric' => 'Level must be a number.'
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
            'name' => 'nullable|string',
            'year_from' => 'required|numeric|regex:/^\d{4}$/',
            'year_to' => 'nullable|numeric|regex:/^\d{4}$/',
            'status' => 'required|numeric',
            'remarks' => 'nullable|string',
            'branch' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'name.string' => 'Name must be a string.',
            'year_from.required' => 'Year From is required.',
            'year_from.regex' => 'Year must be a valid year.',
            'year_to.regex' => 'Year must be a valid year.',
            'status.required' => 'Status is required.',
            'status.numeric' => 'Status must be a number.',
            'remarks.string' => 'Name must be a string.',
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
            'program' => 'required|numeric',
            'name' => 'nullable|string',
            'year_from' => 'required|numeric|regex:/^\d{4}$/',
            'year_to' => 'nullable|numeric|regex:/^\d{4}$/',
            'status' => 'required|numeric',
            'remarks' => 'nullable|string',
            'branch' => 'required|numeric',
        ];

        $customMessages = [
            'program.required' => 'Program is required.',
            'program.numeric' => 'Program must be a number.',
            'name.string' => 'Name must be a string.',
            'year_from.required' => 'Year From is required.',
            'year_from.regex' => 'Year must be a valid year.',
            'year_to.regex' => 'Year must be a valid year.',
            'status.required' => 'Status is required.',
            'status.numeric' => 'Status must be a number.',
            'remarks.string' => 'Name must be a string.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
