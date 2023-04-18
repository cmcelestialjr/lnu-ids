<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function enrollModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;        
        $school_year = EducOfferedSchoolYear::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'school_year' => $school_year,
            'user_access_level' => $user_access_level
        );
        return view('rims/enrollment/enrollModal',$data);
    }
    public function courseAnotherModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $name_services = new NameServices;
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query,
            'name_services' => $name_services
        );
        return view('rims/enrollment/courseAnotherModal',$data);
    }
    public function courseAddModal(Request $request){
        $id = $request->id;
        $query = EducOfferedPrograms::where('school_year_id',$id)->get();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/enrollment/courseAddModal',$data);
    }
    public function enrollmentViewModal(Request $request){
        $id = $request->id;
        $query = EducOfferedPrograms::where('id',$id)->first();
        $curriculum = EducOfferedCurriculum::where('offered_program_id',$id)->orderBy('curriculum_id')->get();
        $section = EducOfferedCourses::whereHas('curriculum', function ($query) use ($id) {
                        $query->where('offered_program_id',$id);
                    })->select('section')
                    ->groupBy('section')
                    ->orderBy('section')
                    ->get();
        $data = array(
            'id' => $id,
            'query' => $query,
            'curriculum' => $curriculum,
            'section' => $section
        );
        return view('rims/enrollment/enrollmentViewModal',$data);
    }
    public function coursesViewModal(Request $request){
        $name_services = new NameServices;
        $id = $request->id;
        $query = StudentsInfo::where('user_id',$id)->first();        
        $name = $name_services->lastname($query->info->lastname,$query->info->firstname,$query->info->middlename,$query->info->extname);
        $data = array(
            'id' => $id,
            'query' => $query,
            'name' => $name
        );
        return view('rims/enrollment/coursesViewModal',$data);
    }
}