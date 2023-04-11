<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class NewController extends Controller
{
    public function sectionNewSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $curriculum = $request->curriculum;
        $grade_level = $request->grade_level;
        $no = $request->no;
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2){            
            try{
                $check = EducOfferedCourses::where('offered_curriculum_id', $curriculum)
                            ->where('year_level',$grade_level)
                            ->orderBy('section','DESC')->first();
                $start_section = $check->section+1;
                for($i=$start_section;$i<$start_section+$no;$i++){
                    $query = EducOfferedCourses::with('curriculum.offered_program')
                                    ->where('offered_curriculum_id',$curriculum)
                                    ->where('year_level',$grade_level)
                                    ->where('section',$check->section)
                                    ->get()
                                    ->map(function($query) use ($updated_by,$grade_level,$i) {
                                    return [
                                            'offered_curriculum_id' => $query->offered_curriculum_id,
                                            'course_id' => $query->course_id,
                                            'min_student' => $query->min_student,
                                            'max_student' => $query->max_student,
                                            'code' => $query->code,
                                            'year_level' => $grade_level,
                                            'section' => $i,
                                            'section_code' => $query->curriculum->code.$query->curriculum->offered_program->name.$i.$grade_level,
                                            'status_id' => 1,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                    ];
                                })->toArray();
                    EducOfferedCourses::insert($query);
                }
                $result = 'success';  
            }catch(Exception $e){
                    
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }    
}