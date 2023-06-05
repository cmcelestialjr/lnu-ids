<?php

namespace App\Http\Controllers\FIS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadViewController extends Controller
{
    public function scheduleTable(Request $request){
        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year = $request->school_year;
        $level = $request->level;
        $query = EducOfferedSchedule::
            whereHas('course', function ($query) use ($instructor_id,$school_year,$level) {
                $query->where('instructor_id',$instructor_id);
                $query->whereHas('curriculum', function ($query) use ($school_year) {
                    $query->whereHas('offered_program', function ($query) use ($school_year) {
                        $query->where('school_year_id',$school_year);
                    });
                });
                $query->whereHas('course', function ($query) use ($level) {
                    $query->whereHas('grade_level', function ($query) use ($level) {
                        if($level==NULL){
                            $query->where('program_level_id','>',0);
                        }else{
                            $query->whereIn('program_level_id',$level);
                        }
                    });
                });
            })->orderBy('time_from','ASC')
            ->get();
        $data = array(
            'query' => $query
        );
        return view('fis/schedule/scheduleTable',$data);
    }
}