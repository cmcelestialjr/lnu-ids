<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCurriculum;
use App\Models\EducLabCourses;
use App\Models\EducLabGroup;
use App\Models\EducProgramsCode;
use Exception;

class NewController extends Controller
{
    public function curriculumNewSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $name = $request->name;
        $remarks = $request->remarks;
        $result = 'error';
        $check = EducCurriculum::where('year_from',$year_from)->where('program_id',$id)->first();
        if($check==NULL){
            try{
                $query = EducCurriculum::where('program_id',$id)->orderBy('year_from','DESC')->first();
                if($query!=NULL){
                    $code = $this->alphabet($query->code);
                }else{
                    $code = 'A';
                }
                $insert = new EducCurriculum(); 
                $insert->program_id = $id;
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->code = $code;
                $insert->name = $name;
                $insert->remarks = $remarks;
                $insert->status_id = 1;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }catch(Exception $e){

            }
        }
        if($result=='success'){
            $curriculums = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->get();
            $data = array(
                'curriculums' => $curriculums
            );
            return view('rims/programs/curriculumSelect',$data);
        }else{
            return $result;
        }
    }
    public function newCourseSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $grade_period = $request->grade_period;
        $year_level = $request->year_level;
        $code = mb_strtoupper($request->code);
        $name = $request->name;
        $units = $request->units;
        $lab = $request->lab;
        $pay_units = $request->pay_units;
        $courses = $request->courses;
        $lab_group = $request->lab_group;
        $course_type = $request->course_type;
        $check = EducCourses::
            where(function ($query) use ($code,$name) {
                $query->where('code',$code)
                    ->orWhere('name',$name);
            })            
            ->where('curriculum_id',$id)->first();
        $result = 'error';
        if($check==NULL){
            try{
                if($courses==NULL){
                    $pre_name = 'None';
                }else{
                    $pre_name = $request->pre_name;
                }
                $insert = new EducCourses(); 
                $insert->curriculum_id = $id;
                $insert->grade_level_id = $year_level;
                $insert->grade_period_id = $grade_period;
                $insert->name = $name;
                $insert->shorten = $code;
                $insert->code = $code;
                $insert->units = $units;
                $insert->lab = $lab;
                $insert->pay_units = $pay_units;
                $insert->pre_name = $pre_name;
                $insert->course_type_id = $course_type;
                $insert->status_id = 1;
                $insert->updated_by = $updated_by;
                $insert->save();
                $course_id = $insert->id;

                if($courses!=NULL){
                    foreach($courses as $course){
                        $insert = new EducCoursesPre(); 
                        $insert->course_id = $course_id;
                        $insert->pre_id = $course;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                if($lab_group!='None'){
                    $check = EducLabGroup::where('id',$lab_group)->first();
                    $check_code = EducLabCourses::where('course_code',$code)->first();
                    $curriculum = EducCurriculum::find($id);
                    $program_level_id = $curriculum->programs->program_level_id;
                    if($check!=NULL && $check_code==NULL){
                        $insert = new EducLabCourses(); 
                        $insert->lab_group_id = $lab_group;
                        $insert->program_level_id = $program_level_id;
                        $insert->course_code = $code;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                $result = 'success';
            }catch(Exception $e){

            }
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function programCodeNewSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $request->id;
        $name = mb_strtoupper($request->name);
        $remarks = $request->remarks;
        if($user_access_level==1 || $user_access_level==2){
            $check = EducProgramsCode::where('name',$name)->first();
            if($check==NULL){
                try{
                    $insert = new EducProgramsCode(); 
                    $insert->program_id = $id;
                    $insert->name = $name;
                    $insert->remarks = $remarks;
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $result = 'success';
                }catch(Exception $e){
                    
                }
            }else{
                $result = 'exists';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function alphabet($letter){
        $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $add = 0;
        if(strlen($letter)>1){
            $add = $letter[1];
            $letter = $letter[0];            
        }        
        if($letter!=NULL){
            $key = array_search($letter, $alphabet);
        }else{
            $key = 0;
        }
        if($letter=='Z'){
            $add = $add+1;
            $key = -1;
        }
        if($add==0){
            $add = '';
        }
        $letter = $alphabet[$key+1].$add;
        return $letter;
    }
}