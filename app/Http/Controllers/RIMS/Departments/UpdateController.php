<?php

namespace App\Http\Controllers\RIMS\Departments;
use App\Http\Controllers\Controller;
use App\Models\EducDepartments;
use App\Models\EducPrograms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateController extends Controller
{
    public function newModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);
        if($user_access_level==1 || $user_access_level==2){
            $check = EducDepartments::where('name', $name)->orWhere('shorten',$shorten)->orWhere('code',$code)->first();
            if($check==NULL){
                try{
                    $insert = new EducDepartments(); 
                    $insert->name = $name;
                    $insert->shorten = $shorten;
                    $insert->code = $code;
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
    public function editModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);
        $result = 'error'; 
        if($user_access_level==1 || $user_access_level==2){
            $check = EducDepartments::where(function ($query) use ($name,$shorten,$code) {
                            $query->where('name', $name)->orWhere('shorten',$shorten)->orWhere('code',$code);
                        })->where('id','<>',$id)->first();
            if($check==NULL){
                try{
                    EducDepartments::where('id', $id)
                    ->update(['name' => $name,
                              'shorten' => $shorten,
                              'code' => $code,
                              'updated_by' => $updated_by,
                              'updated_at' => date('Y-m-d H:i:s')]);
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
    public function programsAddSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $program_id = $request->program_id;
        $result = 'error'; 
        $dept = '';
        if($user_access_level==1 || $user_access_level==2){
            try{
                $query = EducDepartments::where('id',$id)->first();
                $dept = $query->name.' ('.$query->shorten.')';
                EducPrograms::where('id', $program_id)
                    ->update(['department_id' => $id,
                              'updated_by' => $updated_by,
                              'updated_at' => date('Y-m-d H:i:s')]);
                $result = 'success';
            }catch(Exception $e){
                    
            }
        }
        $response = array('result' => $result,
                          'dept' => $dept);
        return response()->json($response);
    }
    
}