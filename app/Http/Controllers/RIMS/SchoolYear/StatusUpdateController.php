<?php

namespace App\Http\Controllers\RIMS\SchoolYear;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function update(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2){
            $selectStatusUpdate = 'selectStatusUpdate'.$request->from;
            $result = $this->$selectStatusUpdate($request);
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function selectStatusUpdateprogram($request){
        $result = 'error';
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $status_id = $request->val;
        try{
            EducOfferedPrograms::where('id', $id)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            EducOfferedCurriculum::where('offered_program_id', $id)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            $curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$id)->pluck('id')->toArray();
            EducOfferedCourses::whereIn('offered_curriculum_id', $curriculum_ids)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            $result = 'success';
        }catch(Exception $e){
            
        }
        return $result;
    }
    private function selectStatusUpdatecurriculum($request){
        $result = 'error';
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $status_id = $request->val;
        try{
            EducOfferedCurriculum::where('id', $id)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            EducOfferedCourses::where('offered_curriculum_id', $id)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            $result = 'success';
        }catch(Exception $e){
            
        }
        return $result;
    }
    private function selectStatusUpdatecourse($request){
        $result = 'error';
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $status_id = $request->val;
        try{
            EducOfferedCourses::where('id', $id)
                        ->update(['status_id' => $status_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                ]);
            $result = 'success';
        }catch(Exception $e){
            
        }
        return $result;
    }
}
