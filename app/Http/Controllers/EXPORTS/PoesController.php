<?php

namespace App\Http\Controllers\EXPORTS;

use App\Exports\Export;
use App\Http\Controllers\Controller;
use App\Models\Poes\PoesProfile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PoesController extends Controller
{
    public function export(){
        $query = PoesProfile::with(['program' => function($q) {
                $q->where('sy_sem_id',11);
                $q->with('program_info');
            }])
        ->whereHas('program',function($q){
            $q->where('sy_sem_id',11);
        })
        ->get()
        ->map(function($query) {
            $birthday = '';                
            $program = '';
            $address = $query->home_address;
            $email = $query->email_address;
            $year_level = '';
            $father_lastname = '';
            $father_firstname = '';
            $father_middlename = '';
            $mother_lastname = '';
            $mother_firstname = '';
            $mother_middlename = '';
            $zip = $query->zip_code;
            $phone_no = $query->contact_number;
            if($query->birthday!=NULL){
                $birthday = date('d/m/Y',strtotime($query->birthday));
            }
            if($query->program){
                foreach($query->program as $row){
                    $program = $row->program_info->program_name;
                    $year_level = $row->year_level;
                }   
            }
            return [
                'stud_id' => $query->student_number,
                'surname' => $query->lastname,
                'first_name' => $query->firstname,
                'middle_name' => $query->middlename,
                'qualifier' => $query->extname,
                'gender' => $query->sex,
                'date_of_birth' => $birthday,
                'course' => $program,
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
}
