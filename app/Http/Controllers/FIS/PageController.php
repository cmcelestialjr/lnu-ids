<?php

namespace App\Http\Controllers\FIS;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'fis';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function information($data){
        return view($this->page.'/information/information',$data);
    }
    public function students($data){
        $user = Auth::user();
        $instructor_id = $user->id;
        $data['school_year'] = EducOfferedSchoolYear::
            whereHas('offered_program', function ($query) use ($instructor_id) {
                $query->whereHas('curriculums', function ($query) use ($instructor_id) {
                    $query->whereHas('offered_courses', function ($query) use ($instructor_id) {
                        $query->where('instructor_id',$instructor_id);
                    });
                });
            })
            ->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/students/students',$data);
    }
    public function subjects($data){
        $user = Auth::user();
        $instructor_id = $user->id;
        $data['school_year'] = EducOfferedSchoolYear::
            whereHas('offered_program', function ($query) use ($instructor_id) {
                $query->whereHas('curriculums', function ($query) use ($instructor_id) {
                    $query->whereHas('offered_courses', function ($query) use ($instructor_id) {
                        $query->where('instructor_id',$instructor_id);
                    });
                });
            })
            ->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/subjects/subjects',$data);
    }
    // public function schedule($data){
    //     $user = Auth::user();
    //     $instructor_id = $user->id;
    //     $data['school_year'] = EducOfferedSchoolYear::
    //         whereHas('offered_program', function ($query) use ($instructor_id) {
    //             $query->whereHas('curriculums', function ($query) use ($instructor_id) {
    //                 $query->whereHas('offered_courses', function ($query) use ($instructor_id) {
    //                     $query->where('instructor_id',$instructor_id);
    //                 });
    //             });
    //         })
    //         ->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
    //     return view($this->page.'/schedule/schedule',$data);
    // }
    public function schedule($data){
        return view($this->page.'/schedule/schedule_muna',$data);
    }
    public function advisement($data){
        $data['school_year'] = EducOfferedSchoolYear::orderBy('year_from','DESC')->get();
        return view($this->page.'/advisement/advisement',$data);
    }
    // public function grades($data){
    //     $level_ids = array(1,2,3); 
    //     $validate = $this->validate->check($data,$level_ids);
    //     if($validate=='success'){
    //         return view($this->page.'/home',$data);
    //     }else{
    //         return view('layouts/error/404');
    //     }
    // }
}
?>