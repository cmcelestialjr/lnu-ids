<?php

namespace App\Http\Controllers\RIMS\Departments;
use App\Http\Controllers\Controller;
use App\Models\EducDepartments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class NewController extends Controller
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
}