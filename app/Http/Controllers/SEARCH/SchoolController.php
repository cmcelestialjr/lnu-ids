<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function school(Request $request){
        $val = $request->val;
        $options = [];
        if($val!=''){
            $options = School::where('name', 'LIKE', "%$val%")
                ->select('name')
                ->orderBy('name')
                ->limit(15)
                ->pluck('name')
                ->toArray();
        }
        return response()->json($options);
    }
    public function school1(Request $request){
        $search = $request->input('search');
        $results = School::where('name', 'LIKE', "%$search%")
                ->orderBy('name')
                ->limit(15)
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