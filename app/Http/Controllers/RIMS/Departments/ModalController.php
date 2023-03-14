<?php

namespace App\Http\Controllers\RIMS\Departments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducPrograms;
use App\Models\EducDepartments;

class ModalController extends Controller
{
    public function newModal(Request $request){
        return view('rims/departments/newModal');
    }
    public function editModal(Request $request){
        $id = $request->id;
        $user_access_level = $request->session()->get('user_access_level');
        $department = EducDepartments::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'department' => $department,
            'user_access_level' => $user_access_level
        );
        return view('rims/departments/editModal',$data);
    }
    public function programsModal(Request $request){
        $id = $request->id;
        $user_access_level = $request->session()->get('user_access_level');
        $department = EducDepartments::where('id',$id)->first();
        $programs = EducPrograms::with('status')->where('department_id',$id)->get();
        $data = array(
            'id' => $id,
            'department' => $department,
            'programs' => $programs,
            'user_access_level' => $user_access_level
        );
        return view('rims/departments/programsModal',$data);
    }
}