<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\Users;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Services\NameServices;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HRISEmployeeImport implements ToModel, WithHeadingRow
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
        if($check==NULL){
            $password = Crypt::encryptString('1234'.Hash::make('1234').'1234');
            $user = Auth::User();
            $user_id = $user->id;

            $middlename = $row['MiddleName'];
            if($row['MiddleName']=='' || $row['MiddleName']=='\N'){
                $middlename = NULL;
            }

            $extname = $row['NameExt'];
            if($row['NameExt']=='' || $row['NameExt']=='\N'){
                $extname = NULL;
            }

            $insert = new Users();
            $insert->username = $row['id_no'];
            $insert->password = $password;
            $insert->lastname = mb_strtoupper($row['LastName']);
            $insert->firstname = mb_strtoupper($row['FirstName']);
            $insert->middlename = mb_strtoupper($middlename);
            $insert->extname = mb_strtoupper($extname);
            $insert->id_no = $row['id_no'];
            $insert->level_id = 6;
            $insert->status_id = 1;
            $insert->user_id = $user_id;
            $insert->save();
            $get_id = $insert->id;

            $check = _PersonalInfo::where('user_id',$get_id)->first();
            if($check==NULL){
                $insert = new _PersonalInfo();
                $insert->email = $row['Email'];
                $insert->user_id = $user_id;
                $insert->save();
            }
        }
    }
}