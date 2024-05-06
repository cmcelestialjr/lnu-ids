<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducPrograms;
use App\Models\EducProgramsAll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function programSearch(Request $request){
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
    public function programSearch2(Request $request){
        $search = $request->input('search');
        $level = $request->level;
        $school = $request->school;
        $data = [];
        if($school<=2){
            $results = EducPrograms::where('program_level_id',$level)
                ->where(function($query) use ($search){
                    $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('shorten', 'LIKE', "%$search%");
                })
                ->orderBy('name')
                ->limit(15)
                ->get();
            if($results->count()>0){
                foreach ($results as $result) {
                    $data[] = ['id' => $result->id, 'text' => $result->shorten.'-'.$result->name];
                }
            }
        }else{
            $results = EducProgramsAll::where('program_level_id',$level)
                ->where('name', 'LIKE', "%$search%")
                ->orderBy('name')
                ->limit(15)
                ->get();
            if($results->count()>0){
                foreach ($results as $result) {
                    $data[] = ['id' => $result->id, 'text' => $result->name];
                }
            }
        }

        return response()->json($data);
    }
}
