<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;
use App\Models\EducPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducGradePeriod;
use App\Models\EducCourseStatus;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'rims';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function students($data){        
        return view($this->page.'/students',$data);
    }
    public function departments($data){   
        return view($this->page.'/departments/departments',$data);
    }
    public function programs($data){   
        $data['statuses'] = EducCourseStatus::get();
        return view($this->page.'/programs/programs',$data);
    }
    public function courses_list($data){        
        return view($this->page.'/courses_list',$data);
    }
    public function sections($data){        
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/sections/sections',$data);
    }
    public function school_year($data){        
        $data['grade_period'] = EducGradePeriod::get();
        return view($this->page.'/schoolYear/school_year',$data);
    }
    public function enrollment($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/enrollment/enrollment',$data);
    }
    public function schedule($data){
        $data['school_year'] = EducOfferedSchoolYear::with('grade_period')->orderBy('grade_period_id','DESC')->orderBy('id','DESC')->get();
        return view($this->page.'/schedule/schedule',$data);
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