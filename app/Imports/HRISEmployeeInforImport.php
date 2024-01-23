<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\Users;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class HRISEmployeeInforImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function headingRow(): int
    {
        return 1;
    } 

    public function model(array $row)
    {
        $check = Users::where('id_no',$row["id_no"])->first();
        if($check!=NULL){
            $user = Auth::User();
            $updated_by = $user->id;

            $checkPersonalInfo = _PersonalInfo::where('user_id',$check->id)->first();

            $dob = $row['DOB'];
            if($dob=='' || $dob=='\N'){
                $dob = NULL;
            }else{
                $dob = date('Y-m-d',strtotime($dob));
            }

            $place_birth = $row['POB_Address'];
            if($place_birth=='' || $place_birth=='\N'){
                $place_birth = NULL;
            }

            $sex = $row['Gender'];
            if($sex=='' || $sex=='\N' || $sex=='1'){
                $sex = 2;
            }else{
                $sex = 1;
            }

            $tin_no = $row['TIN'];
            if($tin_no=='' || $tin_no=='\N'){
                $tin_no = NULL;
            }

            $sss_no = $row['SSS'];
            if($sss_no=='' || $sss_no=='\N'){
                $sss_no = NULL;
            }

            $gsis_bp_no = $row['GSIS'];
            if($gsis_bp_no=='' || $gsis_bp_no=='\N'){
                $gsis_bp_no = NULL;
            }

            $pagibig_no = $row['PAGIBIG'];
            if($pagibig_no=='' || $pagibig_no=='\N'){
                $pagibig_no = NULL;
            }

            $philhealth_no = $row['PHILHEALTH'];
            if($philhealth_no=='' || $philhealth_no=='\N'){
                $philhealth_no = NULL;
            }

            _PersonalInfo::updateOrCreate(
                [      
                    'user_id' => $check->id
                ],
                [
                    'dob' => $dob,
                    'place_birth' => $place_birth,
                    'sex' => $sex,
                    'civil_status_id' => 1,
                    'tin_no' => $tin_no,
                    'sss_no' => $sss_no,
                    'gsis_bp_no' => $gsis_bp_no,
                    'pagibig_no' => $pagibig_no,
                    'philhealth_no' => $philhealth_no,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );           
        }
    }
}