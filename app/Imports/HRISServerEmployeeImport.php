<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HRISServerEmployeeImport implements ToModel, WithHeadingRow
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
        $id_no = $row["id_no"];

        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();
        $user = DB::connection($connectionName)->table('users')->where('id_no',$id_no)->first();
        if($user){
            $this->updateUserServer($id_no,$row);
        }else{
            $this->newUserServer($id_no,$row);
        }
    }
    private function newUserServer($id_no,$row)
    {

    }
    private function updateUserServer($id_no,$row)
    {

    }
}
