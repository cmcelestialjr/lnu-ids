<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use Illuminate\Http\Request;

class CourseCodeController extends Controller
{
    public function courseCode(Request $request){
        $search = $request->input('search');
        $school_year = $request->school_year;
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)
                    ->pluck('id')->toArray();
        $results = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                            ->where('code', 'LIKE', "%$search%")
                            ->select('code')
                            ->groupBy('code')
                            ->orderBy('code')
                            ->limit(15)
                            ->get();                    
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->code, 'text' => $result->code];
            }
        }
        return response()->json($data);
    }
}