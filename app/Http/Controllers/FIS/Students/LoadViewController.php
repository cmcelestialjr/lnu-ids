<?php

namespace App\Http\Controllers\FIS\Students;
use App\Http\Controllers\Controller;
use App\Models\EducProgramLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadViewController extends Controller
{
    public function gradeLevel(Request $request){
        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year = $request->school_year;
        $program_level = array();
        if($school_year!=NULL){
            $program_level = EducProgramLevel::
                    whereHas('year_level.courses.courses', function ($query) use ($school_year,$instructor_id) {
                        $query->where('instructor_id',$instructor_id);
                        $query->whereHas('curriculum.offered_program', function ($query) use ($school_year) {
                            if($school_year=='all'){
                                $query->where('school_year_id','>',0);
                            }else{
                                $query->where('school_year_id',$school_year);
                            }
                        });
                    })->get();
        }
        $data = array(
            'school_year' => $school_year,
            'program_level' => $program_level
        );
        return view('fis/students/gradeLevel',$data);
    }
}