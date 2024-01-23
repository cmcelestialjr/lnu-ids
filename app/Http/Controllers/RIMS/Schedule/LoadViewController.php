<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoadViewController extends Controller
{
    public function searchDiv(Request $request){
        $option = $request->option;        
        if($option=='course_code'){
            return $this->courseCode($request);
        }elseif($option=='course_desc'){
            return $this->courseDesc($request);
        }elseif($option=='section_code'){
            return $this->sectionCode($request);
        }elseif($option=='instructor'){
            return $this->instructor($request);
        }elseif($option=='room'){
            return $this->room($request);
        }else{
            return view('layouts/error/error_page');
        }
    }
    private function courseCode($request){
        $school_year = $request->school_year;
        $data = array(
            'school_year' => $school_year
        );
        return view('SEARCH/courseCode',$data);
    }
    private function courseDesc($request){
        $school_year = $request->school_year;
        $data = array(
            'school_year' => $school_year
        );
        return view('SEARCH/courseDesc',$data);
    }
    private function sectionCode($request){
        $school_year = $request->school_year;
        $data = array(
            'school_year' => $school_year
        );
        return view('SEARCH/sectionCode',$data);
    }
    private function instructor($request){
        $school_year = $request->school_year;
        $data = array(
            'school_year' => $school_year
        );
        return view('SEARCH/instructor',$data);
    }
    private function room($request){
        $school_year = $request->school_year;
        $data = array(
            'school_year' => $school_year
        );
        return view('SEARCH/room',$data);
    }
}