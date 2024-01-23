<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function course(Request $request){
        $search = $request->input('search');
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $results = EducCourses::where('code', 'LIKE', "%$search%")
            ->orWhere('name', 'LIKE', "%$search%")
            ->select('code','name')
            ->groupBy('code')
            ->orderBy('code')
            ->limit(15)
            ->get();                    
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->code, 'text' => $result->code.' - '.$result->name];
            }
        }
        return response()->json($data);
    }
    public function courseID(Request $request){
        $search = $request->input('search');
        $results = EducCourses::where('code', 'LIKE', "%$search%")
            ->orWhere('name', 'LIKE', "%$search%")
            ->orderBy('code')
            ->limit(15)
            ->get();                    
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->curriculum->programs->shorten.'-'.$result->code.'-'.$result->name];
            }
        }
        return response()->json($data);
    }
}