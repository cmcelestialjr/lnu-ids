<?php

namespace App\Http\Controllers\EXPORTS;

use App\Exports\Export;
use App\Http\Controllers\Controller;
use App\Models\EducPrograms;
use App\Models\Ludong\LudongStudents;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LudongController extends Controller
{
    public function export(){
        $query = LudongStudents::with(['classes' => function($q) {
                $q->where('sy',2024);
                $q->where('section','NOT LIKE','%GM%');
            }],'contact','phone','family')
        ->whereHas('classes',function($q){
            $q->where('sy',2024);
            $q->where('section','NOT LIKE','%GM%');
        })
        ->get()
        ->map(function($query) {
            // $updated_by = $name_services->lastname($query->updated_by_id->lastname,$query->updated_by_id->firstname,$query->updated_by_id->middlename,$query->updated_by_id->extname);
            $date_of_birth = '';                
            $course = '';
            $address = '';
            $email = '';
            $year_level = '';
            $father_lastname = '';
            $father_firstname = '';
            $father_middlename = '';
            $mother_lastname = '';
            $mother_firstname = '';
            $mother_middlename = '';
            $zip = '';
            $phone_no = '';
            if($query->date_of_birth!=NULL){
                $date_of_birth = date('d/m/Y',strtotime($query->date_of_birth));
            }
            if($query->classes){
                foreach($query->classes as $row){
                    $section = $row->section;
                    if($section!=''){
                        if(strpos($section, 'TCP') !== false) {
                            $course_code = substr($section,0,3);
                            $year_level = substr($section,3,1);  
                        }else{                   
                            $course_code = substr($section,0,2);
                            $year_level = substr($section,2,1);                            
                        }
                        if(strpos($section, 'XAI') !== false) {
                            $course = 'Bachelor of Science in Information Technology';
                            $year_level = substr($section,3,1);
                        }else{
                            $course_get = EducPrograms::where('code',$course_code)->first();
                            if($course_get){
                                $course = $course_get->name;
                                $year_level = substr($section,2,1);
                            }
                        }
                    }
                }   
            }
            if($query->contact){
                foreach($query->contact as $row){
                    $house_no = $this->checkValue($row->house_no);
                    $building = $this->checkValue($row->building);
                    $street = $this->checkValue($row->street);
                    $village = $this->checkValue($row->village);
                    $barangay = $this->checkValue($row->barangay);
                    $city = $this->checkValue($row->city);
                    $province = $this->checkValue($row->province);
                    $zip = $row->zip;
                    $email = ($row->email=='') ? '' : $row->email;
                    $address = $house_no.$building.$street.$village.$barangay.$city.$province;
                }
            }
            if($query->family){
                foreach($query->family as $row){
                    if($row->type=='Father'){
                        $father_lastname = $row->surname;
                        $father_firstname = $row->first_name;
                        $father_middlename = $row->middle_name;
                    }elseif($row->type=='Mother'){
                        $mother_lastname = $row->surname;
                        $mother_firstname = $row->first_name;
                        $mother_middlename = $row->middle_name;
                    }
                }   
            }
            if($query->phone){
                foreach($query->phone as $row){
                    if($row->phone_type=='Mobile'){
                        $country_code = ($row->country_code=='') ? '' : $row->country_code.'-';
                        $area_code = ($row->area_code=='') ? '' : $row->area_code.'-';
                        $phone_no = $country_code.$area_code.$row->phone_no;
                    }
                }   
            }
            return [
                'stud_id' => $query->stud_id,
                'surname' => $query->surname,
                'first_name' => $query->first_name,
                'middle_name' => $query->middle_name,
                'qualifier' => $query->qualifier,
                'gender' => $query->gender,
                'date_of_birth' => $date_of_birth,
                'course' => $course,
                'year_level' => $year_level,
                'father_lastname' => $father_lastname,
                'father_firstname' => $father_firstname,
                'father_middlename' => $father_middlename,
                'mother_lastname' => $mother_lastname,
                'mother_firstname' => $mother_firstname,
                'mother_middlename' => $mother_middlename,
                'address' => $address,
                'zip' => $zip,
                'phone_no' => $phone_no,
                'email' => $email
                
            ];
        })->toArray();
        return Excel::download(new Export($query), 'products.xlsx');
    }
    public function exportCpanel(){
        
    }
    private function checkValue($value){
        if($value=='' || $value==NULL){
            return '';
        }else{
            return $value.' ';
        }
    }
}
