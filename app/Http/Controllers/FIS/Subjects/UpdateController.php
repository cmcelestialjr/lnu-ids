<?php

namespace App\Http\Controllers\FIS\Subjects;
use App\Http\Controllers\Controller;
use App\Models\StudentsCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class UpdateController extends Controller
{    
    public function studentGradeSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $sid = $request->sid;
        $grade = $request->grade;
        $final_grade = $request->final_grade;
        $result = 'error';
        try{
            if($grade==''){
                $grade = NULL;
            }
            if($final_grade==''){
                $final_grade = NULL;
            }
            StudentsCourses::where('offered_course_id', $id)
                ->where('user_id', $sid)
                ->update(['grade' => $grade,
                    'final_grade' => $final_grade,
                    'graded_by' => $updated_by,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            $result = 'success';
        }catch(Exception $e){
                        
        }
        if($final_grade==NULL){
            $final_grade = 'NG';
        }
        $response = array('result' => $result,
                          'final_grade' => $final_grade
                        );
        return response()->json($response);
    }
}