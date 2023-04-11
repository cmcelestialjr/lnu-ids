<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use Illuminate\Http\Request;

class SectionCodeController extends Controller
{
    public function sectionCode(Request $request){
        $search = $request->input('search');
        $school_year = $request->school_year;
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)
                    ->pluck('id')->toArray();
        $results = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                            ->where('section_code', 'LIKE', "%$search%")
                            ->select('section_code')
                            ->groupBy('section_code')
                            ->orderBy('section_code')
                            ->limit(15)
                            ->get();                    
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->section_code, 'text' => $result->section_code];
            }
        }
        return response()->json($data);
    }
}