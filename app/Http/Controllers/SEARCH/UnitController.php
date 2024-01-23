<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducDepartmentUnit;
use App\Models\HRPosition;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function byDepartment(Request $request){
        $department_id = $request->department_id;
        $search = $request->input('search');
        $results = EducDepartmentUnit::where('name', 'LIKE', "%$search%")
                    ->where('department_id',$department_id)
                    ->orderBy('name')
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}